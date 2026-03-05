<?php

namespace Modules\LandingRateCorrection\Http\Controllers;

use App\Models\Pirep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Modules\LandingRateCorrection\Models\LandingRateCorrection;
use Modules\LandingRateCorrection\Models\NotificationRecipient;
use Modules\LandingRateCorrection\Notifications\CorrectionSubmittedNotification;

class PilotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $threshold  = config('landingratecorecorrection.min_plausible_rate', -20);
        $windowDays = config('landingratecorecorrection.correction_window_days', 0);

        $pireps = Pirep::where('user_id', Auth::id())
            ->where('state', 2)
            ->with(['airline', 'aircraft', 'dpt_airport', 'arr_airport'])
            ->orderByDesc('submitted_at')
            ->paginate(25);

        $correctedIds = LandingRateCorrection::forPilot(Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('pirep_id');

        $implausiblePireps = Pirep::where('user_id', Auth::id())
            ->where('state', 2)
            ->whereNotIn('id', $correctedIds)
            ->where(function ($q) use ($threshold) {
                $q->whereNull('landing_rate')
                  ->orWhere('landing_rate', '>=', $threshold);
            })
            ->with(['airline', 'aircraft', 'dpt_airport', 'arr_airport'])
            ->orderByDesc('submitted_at')
            ->limit(25)
            ->get();

        $corrections = LandingRateCorrection::forPilot(Auth::id())
            ->get()
            ->keyBy('pirep_id');

        $auditLog = LandingRateCorrection::where('pilot_id', Auth::id())
            ->with(['pirep.airline', 'admin'])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return view('landingratecorecorrection::pilot.index',
            compact('pireps', 'corrections', 'windowDays', 'threshold',
                    'implausiblePireps', 'auditLog'));
    }

    public function create(string $pirepId)
    {
        $pirep = Pirep::with(['airline', 'aircraft', 'dpt_airport', 'arr_airport'])
            ->findOrFail($pirepId);

        if ((int) $pirep->user_id !== (int) Auth::id()) abort(403);

        if (LandingRateCorrection::where('pirep_id', $pirepId)->pending()->exists()) {
            return redirect()->route('lrc.pilot.index')
                ->with('warning', 'A pending request already exists for this PIREP.');
        }

        $windowDays = config('landingratecorecorrection.correction_window_days', 0);
        if ($windowDays > 0 && $pirep->submitted_at?->diffInDays(now()) > $windowDays) {
            return redirect()->route('lrc.pilot.index')
                ->with('error', "This PIREP is older than {$windowDays} days and can no longer be corrected.");
        }

        return view('landingratecorecorrection::pilot.create', compact('pirep'));
    }

    public function store(Request $request, string $pirepId)
    {
        $pirep = Pirep::findOrFail($pirepId);
        if ((int) $pirep->user_id !== (int) Auth::id()) abort(403);

        // Remove old rejected requests so pilot can re-apply
        LandingRateCorrection::where('pirep_id', $pirepId)
            ->where('status', 'rejected')
            ->delete();

        $mimes   = implode(',', config('landingratecorecorrection.allowed_mimes', ['jpg','jpeg','png','gif','pdf']));
        $maxSize = config('landingratecorecorrection.max_upload_size', 5120);

        $validated = $request->validate([
            'requested_landing_rate' => ['required', 'integer', 'between:-9999,-1'],
            'reason'                 => ['required', 'string', 'min:10', 'max:2000'],
            'notify_on_decision'     => ['nullable'],
            'evidence'               => ["nullable", "file", "mimes:{$mimes}", "max:{$maxSize}"],
        ], [
            'requested_landing_rate.required' => 'Please enter a landing rate.',
            'requested_landing_rate.between'  => 'Landing rate must be between -9999 and -1 ft/min.',
            'reason.min'                      => 'Please enter at least 10 characters as your reason.',
            'evidence.mimes'                  => 'Only JPG, PNG, GIF or PDF files are allowed.',
            'evidence.max'                    => 'File must not exceed 5 MB.',
        ]);

        // Handle file upload
        $evidencePath         = null;
        $evidenceOriginalName = null;

        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');

            if (!$file->isValid()) {
                Log::error('[LRC] Upload invalid: error=' . $file->getError() . ' msg=' . $file->getErrorMessage());
                return back()->withInput()->withErrors(['evidence' => 'Upload failed: ' . $file->getErrorMessage()]);
            }

            try {
                // Ensure the storage directory exists on disk
                $disk    = Storage::disk('public');
                $absDir  = $disk->path('lrc_evidence');
                if (!is_dir($absDir)) {
                    mkdir($absDir, 0775, true);
                }

                $evidencePath         = $file->store('lrc_evidence', 'public');
                // Sanitize original filename before storing (strip control chars)
                $evidenceOriginalName = preg_replace('/[^\w.\-\s]/', '_', $file->getClientOriginalName());

                Log::info('[LRC] Upload OK: ' . $evidencePath);

                if (!$evidencePath) {
                    Log::error('[LRC] store() returned false – check storage/app/public is writable');
                    return back()->withInput()->withErrors(['evidence' => 'File could not be saved. Please contact an admin.']);
                }
            } catch (\Exception $e) {
                Log::error('[LRC] Upload exception: ' . $e->getMessage());
                return back()->withInput()->withErrors(['evidence' => 'Upload error: ' . $e->getMessage()]);
            }
        }

        $correction = LandingRateCorrection::create([
            'pirep_id'               => $pirepId,
            'pilot_id'               => Auth::id(),
            'original_landing_rate'  => $pirep->landing_rate ?? 0,
            'requested_landing_rate' => $validated['requested_landing_rate'],
            'reason'                 => $validated['reason'],
            'notify_on_decision'     => $request->has('notify_on_decision') ? 1 : 0,
            'evidence_path'          => $evidencePath,
            'evidence_original_name' => $evidenceOriginalName,
            'status'                 => 'pending',
        ]);

        // Send admin notifications – single call to avoid multiple SMTP connections
        try {
            $recipientIds = NotificationRecipient::pluck('user_id');
            if ($recipientIds->isNotEmpty()) {
                $admins = User::whereIn('id', $recipientIds)->get();
                Notification::send($admins, new CorrectionSubmittedNotification($correction));
            }
        } catch (\Exception $e) {
            Log::warning('[LRC] Notification failed: ' . $e->getMessage());
        }

        return redirect()->route('lrc.pilot.index')
            ->with('success', 'Your correction request has been submitted and is awaiting admin review.');
    }
}

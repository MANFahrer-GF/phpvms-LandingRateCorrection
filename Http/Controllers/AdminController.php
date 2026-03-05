<?php

namespace Modules\LandingRateCorrection\Http\Controllers;

use App\Models\Pirep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\LandingRateCorrection\Models\LandingRateCorrection;
use Modules\LandingRateCorrection\Models\NotificationRecipient;
use Modules\LandingRateCorrection\Notifications\CorrectionProcessedNotification;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $tab          = $request->query('tab', 'pending');
        $pendingCount = LandingRateCorrection::where('status', 'pending')->count();

        // Always load for settings tab
        $recipientIds = NotificationRecipient::pluck('user_id')->toArray();
        $admins       = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))
                            ->orderBy('name')
                            ->get();

        $corrections = collect();
        $auditLog    = collect();

        if ($tab === 'audit') {
            $auditLog = LandingRateCorrection::with(['pirep.airline', 'pilot', 'admin'])
                ->orderByDesc('created_at')
                ->paginate(30)
                ->withQueryString();

        } elseif ($tab === 'settings') {
            // $admins + $recipientIds already loaded above

        } else {
            $statusFilter = in_array($tab, ['pending', 'approved', 'rejected']) ? $tab : null;
            $corrections  = LandingRateCorrection::with(['pirep.airline', 'pilot', 'admin'])
                ->when($statusFilter, fn($q) => $q->where('status', $statusFilter))
                ->orderByDesc('created_at')
                ->paginate(25)
                ->withQueryString();
        }

        return view('landingratecorecorrection::admin.index',
            compact('corrections', 'auditLog', 'tab', 'pendingCount', 'admins', 'recipientIds'));
    }

    public function implausible(Request $request)
    {
        $tab       = $request->query('tab', 'implausible');
        $threshold = config('landingratecorecorrection.min_plausible_rate', -20);

        $implausibleCount = Pirep::where(function ($q) use ($threshold) {
            $q->whereNull('landing_rate')->orWhere('landing_rate', '>=', $threshold);
        })->count();

        $admins       = collect();
        $recipientIds = [];

        $pireps   = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 30);
        $auditLog = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 30);

        if ($tab === 'implausible') {
            $pireps = Pirep::where(function ($q) use ($threshold) {
                $q->whereNull('landing_rate')->orWhere('landing_rate', '>=', $threshold);
            })
            ->with(['user', 'airline', 'dpt_airport', 'arr_airport'])
            ->orderByDesc('submitted_at')
            ->paginate(30)
            ->withQueryString();

        } elseif ($tab === 'audit') {
            $auditLog = LandingRateCorrection::with(['pirep.airline', 'pilot', 'admin'])
                ->orderByDesc('created_at')
                ->paginate(30)
                ->withQueryString();
        }

        return view('landingratecorecorrection::admin.implausible',
            compact('pireps', 'auditLog', 'tab', 'threshold',
                    'admins', 'recipientIds', 'implausibleCount'));
    }

    public function saveRecipients(Request $request)
    {
        $ids = array_filter(array_map('intval', $request->input('recipients', [])));
        NotificationRecipient::truncate();
        foreach ($ids as $userId) {
            NotificationRecipient::create(['user_id' => $userId]);
        }
        return back()->with('success', 'Notification recipients saved.');
    }

    public function fixImplausible(Request $request, string $pirepId)
    {
        $pirep = Pirep::with('user')->findOrFail($pirepId);

        $validated = $request->validate([
            'landing_rate' => ['required', 'integer', 'between:-9999,-1'],
            'admin_note'   => ['required', 'string', 'min:3', 'max:500'],
        ]);

        $existing = LandingRateCorrection::where('pirep_id', $pirepId)->latest()->first();

        if ($existing) {
            $existing->update([
                'original_landing_rate'  => $pirep->landing_rate ?? 0,
                'requested_landing_rate' => $validated['landing_rate'],
                'status'                 => 'approved',
                'admin_id'               => Auth::id(),
                'admin_note'             => $validated['admin_note'],
                'processed_at'           => now(),
            ]);
        } else {
            LandingRateCorrection::create([
                'pirep_id'               => $pirepId,
                'pilot_id'               => $pirep->user_id,
                'original_landing_rate'  => $pirep->landing_rate ?? 0,
                'requested_landing_rate' => $validated['landing_rate'],
                'reason'                 => '[Admin Direct Fix] ' . $validated['admin_note'],
                'status'                 => 'approved',
                'admin_id'               => Auth::id(),
                'admin_note'             => $validated['admin_note'],
                'processed_at'           => now(),
            ]);
        }

        $pirep->update(['landing_rate' => $validated['landing_rate']]);
        return back()->with('success', 'Landing rate corrected for ' . (($pirep->airline?->icao ?? '') . $pirep->flight_number));
    }

    public function show(int $correctionId)
    {
        $correction = LandingRateCorrection::with(['pirep.airline', 'pilot', 'admin'])
            ->findOrFail($correctionId);

        $pilotName = $correction->pilot ? ($correction->pilot->name ?? '–') : '–';
        $adminName = $correction->admin ? ($correction->admin->name ?? '–') : '–';

        return view('landingratecorecorrection::admin.show',
            compact('correction', 'pilotName', 'adminName'));
    }

    public function approve(Request $request, int $correctionId)
    {
        $correction = LandingRateCorrection::with(['pirep', 'pilot'])->findOrFail($correctionId);
        if (!$correction->isPending()) return back()->with('error', 'Already processed.');

        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);

        $correction->update([
            'status'       => 'approved',
            'admin_id'     => Auth::id(),
            'admin_note'   => $validated['admin_note'] ?? null,
            'processed_at' => now(),
        ]);

        if ($correction->pirep) {
            $correction->pirep->update(['landing_rate' => $correction->requested_landing_rate]);
        }

        if ($correction->notify_on_decision && $correction->pilot?->email) {
            try {
                Notification::route('mail', $correction->pilot->email)
                    ->notify(new CorrectionProcessedNotification($correction));
            } catch (\Exception $e) {
                Log::warning('[LRC] Pilot notification failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('lrc.admin.index')->with('success', 'Correction approved and applied.');
    }

    public function reject(Request $request, int $correctionId)
    {
        $correction = LandingRateCorrection::with(['pirep', 'pilot'])->findOrFail($correctionId);
        if (!$correction->isPending()) return back()->with('error', 'Already processed.');

        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'min:3', 'max:1000'],
        ], ['admin_note.required' => 'Please provide a reason for rejection.']);

        $correction->update([
            'status'       => 'rejected',
            'admin_id'     => Auth::id(),
            'admin_note'   => $validated['admin_note'],
            'processed_at' => now(),
        ]);

        if ($correction->notify_on_decision && $correction->pilot?->email) {
            try {
                Notification::route('mail', $correction->pilot->email)
                    ->notify(new CorrectionProcessedNotification($correction));
            } catch (\Exception $e) {
                Log::warning('[LRC] Pilot notification failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('lrc.admin.index')->with('success', 'Correction rejected.');
    }
}

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Modules\LandingRateCorrection\Http\Controllers\AdminController;
use Modules\LandingRateCorrection\Http\Controllers\PilotController;

/*
|--------------------------------------------------------------------------
| Pilot Routes
|--------------------------------------------------------------------------
*/
Route::prefix('lrc')->name('lrc.pilot.')->middleware('auth')->group(function () {
    Route::get('/',                 [PilotController::class, 'index'])->name('index');
    Route::get('/create/{pirepId}', [PilotController::class, 'create'])->name('create');
    Route::post('/store/{pirepId}', [PilotController::class, 'store'])->name('store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin/lrc')->name('lrc.admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/',                           [AdminController::class, 'index'])->name('index');
    Route::get('/implausible',                [AdminController::class, 'implausible'])->name('implausible');
    Route::post('/implausible/{pirepId}/fix', [AdminController::class, 'fixImplausible'])->name('fix_implausible');
    Route::post('/recipients',                [AdminController::class, 'saveRecipients'])->name('save_recipients');
    Route::post('/mail-templates',            [AdminController::class, 'saveMailTemplates'])->name('save_mail_templates');
    Route::post('/appearance',                [AdminController::class, 'saveAppearance'])->name('save_appearance');
    Route::post('/appearance/reset',          [AdminController::class, 'resetAppearance'])->name('reset_appearance');
    Route::get('/{correctionId}',             [AdminController::class, 'show'])->name('show');
    Route::post('/{correctionId}/approve',    [AdminController::class, 'approve'])->name('approve');
    Route::post('/{correctionId}/reject',     [AdminController::class, 'reject'])->name('reject');
});

/*
|--------------------------------------------------------------------------
| Evidence File Serving
| Uses Storage::disk('public') – works without storage:link
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/lrc/evidence/{filename}', function (string $filename) {
    // Prevent path traversal and header injection
    $filename = basename($filename);
    // Strip characters that could break Content-Disposition header
    $safeFilename = preg_replace('/[^\w.\-]/', '_', $filename);
    $disk     = Storage::disk('public');
    $path     = 'lrc_evidence/' . $filename;

    // Try Storage disk first
    if ($disk->exists($path)) {
        $fullPath = $disk->path($path);
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
        return response()->file($fullPath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $safeFilename . '"',
        ]);
    }

    // Fallback: search in common phpVMS public upload directories
    $candidates = [
        public_path('uploads/lrc_evidence/' . $filename),
        public_path('storage/lrc_evidence/' . $filename),
        storage_path('app/public/lrc_evidence/' . $filename),
    ];

    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            $mimeType = mime_content_type($candidate) ?: 'application/octet-stream';
            return response()->file($candidate, [
                'Content-Type'        => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        }
    }

    abort(404, 'Evidence file not found.');
})->name('lrc.evidence');

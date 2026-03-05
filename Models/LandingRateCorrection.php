<?php

namespace Modules\LandingRateCorrection\Models;

use App\Contracts\Model;
use App\Models\User;
use App\Models\Pirep;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingRateCorrection extends Model
{
    protected $table = 'landing_rate_corrections';

    protected $fillable = [
        'pirep_id', 'pilot_id', 'admin_id',
        'original_landing_rate', 'requested_landing_rate',
        'reason', 'status', 'admin_note',
        'notify_on_decision', 'evidence_path', 'evidence_original_name',
        'processed_at',
    ];

    protected $casts = [
        'notify_on_decision' => 'boolean',
        'processed_at'       => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────
    public function pirep(): BelongsTo
    {
        return $this->belongsTo(Pirep::class, 'pirep_id', 'id');
    }

    public function pilot(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pilot_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForPilot($query, int $id)
    {
        return $query->where('pilot_id', $id);
    }

    // ── Status helpers ─────────────────────────────────────────────────────
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function statusLabel(): string
    {
        return match($this->status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default    => 'Pending',
        };
    }

    // ── Evidence ───────────────────────────────────────────────────────────
    public function hasEvidence(): bool
    {
        return !empty($this->evidence_path);
    }

    /**
     * Always serve via controller route – no storage:link needed.
     * The controller reads directly from storage_path().
     */
    public function evidenceUrl(): ?string
    {
        if (!$this->evidence_path) return null;

        // Always use our controller route – avoids phpVMS router intercepting
        // the public disk URL (uploads/lrc_evidence/...)
        return route('lrc.evidence', ['filename' => basename($this->evidence_path)]);
    }

    public function isImage(): bool
    {
        if (!$this->evidence_original_name) return false;
        return in_array(
            strtolower(pathinfo($this->evidence_original_name, PATHINFO_EXTENSION)),
            ['jpg', 'jpeg', 'png', 'gif', 'webp']
        );
    }
}

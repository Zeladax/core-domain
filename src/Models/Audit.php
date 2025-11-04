<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use IncadevUns\CoreDomain\Enums\AuditStatus;

/**
 * @property int $id
 * @property int|null $auditor_id
 * @property \Illuminate\Support\Carbon $audit_date
 * @property string|null $summary
 * @property AuditStatus $status
 * @property string $auditable_type
 * @property int $auditable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\AuditAction> $actions
 * @property-read int|null $actions_count
 * @property-read Model|\Eloquent $auditable
 * @property-read \Illuminate\Foundation\Auth\User|null $auditor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\AuditFinding> $findings
 * @property-read int|null $findings_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereAuditDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereAuditableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereAuditableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereAuditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Audit whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Audit extends Model
{
    protected $fillable = [
        'auditor_id',
        'audit_date',
        'summary',
        'recommendation',
        'path_report',
        'status',
        'auditable_id',
        'auditable_type',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'status' => AuditStatus::class,
    ];

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'auditor_id');
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function findings(): HasMany
    {
        return $this->hasMany(AuditFinding::class);
    }

    public function actions(): HasManyThrough
    {
        return $this->hasManyThrough(AuditAction::class, AuditFinding::class);
    }
}

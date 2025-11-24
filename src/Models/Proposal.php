<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Proposal Model
 *
 * Represents a proposal for marketing campaigns.
 * A proposal can have multiple campaigns associated with it.
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $area
 * @property string $priority
 * @property string $status
 * @property string $target_audience
 * @property int $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Campaign> $campaigns
 */
class Proposal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'area',
        'priority',
        'status',
        'target_audience',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with the user who created the proposal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'), 'created_by');
    }

    /**
     * Relationship with campaigns.
     * A proposal can have multiple campaigns.
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }
}

<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Proposal model.
 * Represents a proposal associated with a campaign.
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $title
 * @property string|null $content
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Campaign $campaign
 */
class Proposal extends Model
{
    protected $fillable = [
        'campaign_id',
        'title',
        'content',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}

<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Post model.
 * Represents a social media post for a campaign.
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $title
 * @property string $platform
 * @property string|null $content
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Campaign $campaign
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Metric> $metrics
 */
class Post extends Model
{
    protected $fillable = [
        'campaign_id',
        'title',
        'platform',
        'content',
        'image_url',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(Metric::class);
    }
}

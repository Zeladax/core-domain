<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Post Model
 *
 * Represents a social media post for a campaign.
 * Each post belongs to a campaign and can have multiple metrics.
 *
 * @property int $id
 * @property int|null $campaign_id
 * @property string|null $meta_post_id
 * @property string $title
 * @property string $platform
 * @property string|null $content
 * @property string $content_type
 * @property string|null $image_path
 * @property string|null $link_url
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Campaign|null $campaign
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Metric> $metrics
 */
class Post extends Model
{
    protected $fillable = [
        'campaign_id',
        'meta_post_id',
        'title',
        'platform',
        'content',
        'content_type',
        'image_path',
        'link_url',
        'status',
        'scheduled_at',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with campaign.
     * Each post belongs to a campaign.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Relationship with metrics.
     * Each post can have multiple metrics.
     */
    public function metrics(): HasMany
    {
        return $this->hasMany(Metric::class);
    }

    /**
     * Relationship with the user who created the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'), 'created_by');
    }
}

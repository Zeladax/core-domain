<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Metric Model
 *
 * Represents metrics for campaigns and posts from Facebook and Instagram.
 * Can store consolidated campaign metrics or individual post metrics.
 *
 * @property int $id
 * @property int|null $campaign_id
 * @property int|null $post_id
 * @property string $platform
 * @property string|null $meta_post_id
 * @property int $views
 * @property int $likes
 * @property int $comments
 * @property int $shares
 * @property int $engagement
 * @property int $reach
 * @property int $impressions
 * @property int $saves
 * @property \Illuminate\Support\Carbon $metric_date
 * @property string $metric_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Campaign|null $campaign
 * @property-read \IncadevUns\CoreDomain\Models\Post|null $post
 */
class Metric extends Model
{
    protected $fillable = [
        // 'campaign_id',
        'post_id',
        'platform',
        'meta_post_id',
        'views',
        'likes',
        'comments',
        'shares',
        'engagement',
        'reach',
        'impressions',
        'saves',
        'metric_date',
        'metric_type',
    ];

    protected $casts = [
        'metric_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // /**
    //  * Relationship with campaign.
    //  */
    // public function campaign(): BelongsTo
    // {
    //     return $this->belongsTo(Campaign::class);
    // }

    /**
     * Relationship with post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scope for campaign metrics.
     */
    public function scopeCampaignMetrics($query)
    {
        return $query->whereNull('post_id');
    }

    /**
     * Scope for post metrics.
     */
    public function scopePostMetrics($query)
    {
        return $query->whereNotNull('post_id');
    }

    /**
     * Scope by platform.
     */
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope by metric type.
     */
    public function scopeMetricType($query, $type)
    {
        return $query->where('metric_type', $type);
    }

    /**
     * Calculate engagement automatically.
     */
    public function calculateEngagement()
    {
        return $this->likes + $this->comments + $this->shares + $this->saves;
    }
}

<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Post Model
 *
 * Represents a social media post for a campaign.
 * Each post belongs to a campaign and has one metric associated.
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $title
 * @property string $platform
 * @property string|null $content
 * @property string $content_type
 * @property string|null $image_path
 * @property string|null $link_url
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Campaign $campaign
 * @property-read \IncadevUns\CoreDomain\Models\Metric $metric
 */
class Post extends Model
{
    protected $fillable = [
        'campaign_id',
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
     * Relationship with metric.
     * Each post has one metric associated.
     */
    public function metric(): HasOne
    {
        return $this->hasOne(Metric::class);
    }

    /**
     * Relationship with the user who created the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'), 'created_by');
    }
}

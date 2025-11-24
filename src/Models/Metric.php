<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Metric Model
 *
 * Represents metrics for a post, including various engagement and performance indicators.
 * Each metric belongs to a post.
 *
 * @property int $id
 * @property int $post_id
 * @property int $messages_received
 * @property int $pre_registrations
 * @property float $intention_percentage
 * @property int $total_reach
 * @property int $total_interactions
 * @property float $ctr_percentage
 * @property int $likes
 * @property int $comments
 * @property int $private_messages
 * @property int $expected_enrollments
 * @property float $cpa_cost
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Post $post
 */
class Metric extends Model
{
    protected $fillable = [
        'post_id',
        'messages_received',
        'pre_registrations',
        'intention_percentage',
        'total_reach',
        'total_interactions',
        'ctr_percentage',
        'likes',
        'comments',
        'private_messages',
        'expected_enrollments',
        'cpa_cost',
    ];

    protected $casts = [
        'intention_percentage' => 'decimal:2',
        'ctr_percentage' => 'decimal:2',
        'cpa_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with post.
     * Each metric belongs to a post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

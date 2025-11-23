<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Metric model.
 * Represents metrics for a post, such as views, likes, comments, and shares.
 *
 * @property int $id
 * @property int $post_id
 * @property int $views
 * @property int $likes
 * @property int $comments
 * @property int $shares
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Post $post
 */
class Metric extends Model
{
    protected $fillable = [
        'post_id',
        'views',
        'likes',
        'comments',
        'shares',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

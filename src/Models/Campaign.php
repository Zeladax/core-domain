<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Campaign model.
 * Represents a marketing campaign.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Proposal> $proposals
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Post> $posts
 */
class Campaign extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

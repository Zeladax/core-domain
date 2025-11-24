<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Campaign Model
 *
 * Represents a marketing campaign associated with a proposal and course version.
 * A campaign can have multiple posts.
 *
 * @property int $id
 * @property int $proposal_id
 * @property int $course_version_id
 * @property string $name
 * @property string|null $objective
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \IncadevUns\CoreDomain\Models\Proposal $proposal
 * @property-read \IncadevUns\CoreDomain\Models\CourseVersion $courseVersion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \IncadevUns\CoreDomain\Models\Post> $posts
 */
class Campaign extends Model
{
    protected $fillable = [
        'proposal_id',
        'course_version_id',
        'name',
        'objective',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with Proposal.
     * A campaign belongs to a proposal.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Relationship with CourseVersion.
     * A campaign belongs to a course version.
     */
    public function courseVersion(): BelongsTo
    {
        return $this->belongsTo(CourseVersion::class);
    }

    /**
     * Relationship with posts.
     * A campaign can have multiple posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

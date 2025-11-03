<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IncadevUns\CoreDomain\Enums\CourseVersionStatus;
use IncadevUns\CoreDomain\Traits\CanBeAudited;

class CourseVersion extends Model
{
    use CanBeAudited;

    protected $fillable = [
        'course_id',
        'version',
        'name',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => CourseVersionStatus::class,
        'price' => 'decimal:2',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}

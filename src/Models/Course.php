<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IncadevUns\CoreDomain\Traits\CanBeAudited;

class Course extends Model
{
    use CanBeAudited;

    protected $fillable = [
        'name',
        'description',
        'image_path',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(CourseVersion::class);
    }
}

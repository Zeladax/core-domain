<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iniciative extends Model
{
    use HasFactory;

    protected $table = 'iniciatives';

    protected $fillable = [
        'title',
        'plan_id',
        'summary',
        'user_id',
        'status',
        'start_date',
        'end_date',
        'estimated_impact',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(StrategicPlan::class, 'plan_id');
    }

    public function user()
    {
        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        return $this->belongsTo($userModel, 'user_id');
    }

    public function evaluations()
    {
        return $this->hasMany(IniciativeEvaluation::class, 'iniciative_id');
    }
}

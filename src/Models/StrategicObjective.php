<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicObjective extends Model
{
    use HasFactory;

    protected $table = 'strategic_objectives';

    protected $fillable = [
        'plan_id',
        'title',
        'description',
        'goal_value',
        'user_id',
        'weight',
        'kpis',
    ];

    protected $casts = [
        'goal_value' => 'decimal:2',
        'weight' => 'integer',
        'kpis' => 'array',
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
}

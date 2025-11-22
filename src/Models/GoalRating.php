<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoalRating
 *
 * @property int $id
 * @property int $strategic_goal_id
 * @property int $user_id
 * @property float|int $score
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read StrategicGoal $strategicGoal
 * @property-read \Illuminate\Foundation\Auth\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating whereStrategicGoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalRating whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GoalRating extends Model
{
    protected $table = 'goal_ratings';

    protected $fillable = [
        'strategic_goal_id',
        'user_id',
        'score',
        'comment',
    ];

    public function strategicGoal()
    {
        return $this->belongsTo(StrategicGoal::class);
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'));
    }
}

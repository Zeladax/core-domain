<?php

namespace IncadevUns\CoreDomain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativeEvaluation extends Model
{
    use HasFactory;

    protected $table = 'iniciative_evaluations';

    protected $fillable = [
        'iniciative_id',
        'evaluator_user',
        'summary',
        'score',
        'document_id',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function iniciative()
    {
        return $this->belongsTo(Iniciative::class, 'iniciative_id');
    }

    public function evaluator()
    {
        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        return $this->belongsTo($userModel, 'evaluator_user');
    }

    public function document()
    {
        return $this->belongsTo(StrategicDocument::class, 'document_id');
    }
}

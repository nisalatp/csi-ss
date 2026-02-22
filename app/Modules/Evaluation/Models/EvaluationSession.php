<?php

namespace App\Modules\Evaluation\Models;

use App\Models\User;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationSession extends Model
{
    use HasFactory, Hashidable;

    protected $table = 'evaluation_sessions';

    protected $fillable = [
        'template_id',
        'evaluatee_id',
        'evaluator_id',
        'status',
        'submitted_at',
        'completed_at',
        'final_comments',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(EvaluationTemplate::class, 'template_id');
    }

    public function evaluatee()
    {
        return $this->belongsTo(User::class, 'evaluatee_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class, 'evaluation_session_id');
    }
}

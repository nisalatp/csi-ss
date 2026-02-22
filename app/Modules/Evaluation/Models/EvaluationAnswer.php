<?php

namespace App\Modules\Evaluation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationAnswer extends Model
{
    use HasFactory;

    protected $table = 'evaluation_answers';

    protected $fillable = [
        'evaluation_session_id',
        'question_id',
        'score',
        'comment',
    ];

    public function session()
    {
        return $this->belongsTo(EvaluationSession::class, 'evaluation_session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}

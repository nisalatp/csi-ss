<?php

namespace App\Modules\Evaluation\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory, Hashidable;

    protected $table = 'evaluation_questions';

    protected $fillable = [
        'name',
        'content',
        'category',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function templates()
    {
        return $this->belongsToMany(EvaluationTemplate::class, 'evaluation_template_question', 'question_id', 'template_id')
                    ->withPivot('order')
                    ->withTimestamps();
    }
}

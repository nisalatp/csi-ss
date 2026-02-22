<?php

namespace App\Modules\Evaluation\Models;

use App\Models\Company;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationTemplate extends Model
{
    use HasFactory, Hashidable;

    protected $table = 'evaluation_templates';

    protected $fillable = [
        'name',
        'description',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'evaluation_template_question', 'template_id', 'question_id')
                    ->withPivot('order')
                    ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(EvaluationSession::class, 'template_id');
    }
}

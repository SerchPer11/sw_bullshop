<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $table = 'survey_questions';

    protected $fillable = [
        'survey_id',
        'type',
        'question',
        'placeholder',
        'code',
        'options',
        'icon',
        'is_required',
        'order',
        'validation_rules',
        'ui_config',
    ];

    protected $casts = [
        'options' => 'array',
        'ui_config' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}

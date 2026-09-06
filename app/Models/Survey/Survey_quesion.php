<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use App\Models\Survey\Survey;

class Survey_quesion extends Model
{
    protected $table = 'survey_quesions';

    protected $fillable = [
        'survey_id',
        'type',
        'question',
        'options',
        'is_required',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}

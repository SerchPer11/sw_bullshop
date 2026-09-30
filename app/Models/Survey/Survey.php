<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id');
    }
}

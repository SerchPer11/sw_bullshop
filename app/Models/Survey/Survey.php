<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use App\Models\Survey\Survey_quesion;

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
        return $this->hasMany(Survey_quesion::class, 'survey_id');
    }
}

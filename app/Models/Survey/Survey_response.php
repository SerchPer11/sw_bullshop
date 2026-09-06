<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;

class Survey_response extends Model
{
    protected $table = 'survey_responses';

    protected $fillable = [
        'survey_id',
        'user_id',
        'lead_id',
        'responses',
    ];

    protected $casts = [
        'responses' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}

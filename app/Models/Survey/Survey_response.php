<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Bussines\Lead;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}

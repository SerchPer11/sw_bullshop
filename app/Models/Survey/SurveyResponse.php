<?php

namespace App\Models\Survey;

use App\Models\Bussines\Lead;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class SurveyResponse extends Model
{
    use HasUlids;
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

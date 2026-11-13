<?php

namespace App\Models\Survey;

use App\Models\Bussiness\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

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

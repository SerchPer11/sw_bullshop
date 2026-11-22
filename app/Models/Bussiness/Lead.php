<?php

namespace App\Models\Bussiness;

use App\Models\Survey\SurveyResponse;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasUlids;

    protected $table = 'leads';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'source',
        'converted_user_id',
    ];

    public function convertedUser()
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'lead_id');
    }
}

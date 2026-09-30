<?php

namespace App\Models\Bussines;

use App\Models\Survey\SurveyResponse;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Lead extends Model
{
    use HasUlids;
    
    protected $table = 'leads';

    protected $fillable = [
        'name',
        'lastname',
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

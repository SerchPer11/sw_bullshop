<?php

namespace App\Models\Bussines;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Lead extends Model
{
    protected $table = 'leads';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'converted_user_id',
    ];

    public function convertedUser()
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }
}

<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Users\User;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';

    protected $fillable = [
        'user_id',
        'name',
        'breed',
        'birth_date',
        'weight',
        'sex',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $table = 'customer_profiles';

    protected $fillable = [
        'user_id',
        'gender',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

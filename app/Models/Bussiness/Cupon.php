<?php

namespace App\Models\Bussiness;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cupons';

    protected $fillable = [
        'code',
        'discount',
        'start_date',
        'expiration_date',
        'is_active',
    ];
}

<?php

namespace App\Models\Bussines;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'packages';

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'price',
        'is_active',
    ];

    
}

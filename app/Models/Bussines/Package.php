<?php

namespace App\Models\Bussines;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bussines\Product;
use App\Models\Photo;

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
        'compare_at_price',
        'stock',
        'is_featured',
        'is_active',
        'limit_per_user'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_products', 'package_id', 'product_id');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Bussines\Product;

class Product_category extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}

<?php

namespace App\Models\Bussiness;

use App\Models\Catalogs\ProductCategory;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'description',
        'is_featured',
        'is_active',
        'limit_per_user',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_products', 'product_id', 'package_id')
            ->withPivot('quantity');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

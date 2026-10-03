<?php

namespace App\Models\Bussiness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalogs\ProductCategory;
use App\Models\Bussiness\ReservationItem;
use App\Models\Bussiness\Package;
use App\Models\Photo;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_category_id',
        'sku',
        'name',
        'slug',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'attributes',
        'is_active',
        'is_featured',
        'limit_per_user',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class, 'product_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_products', 'product_id', 'package_id');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

}

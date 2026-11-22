<?php

namespace App\Models\Bussiness;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, hasUlids, SoftDeletes;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'compare_at_price',
        'is_active',
        'is_featured',
        'limit_per_user',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_products', 'package_id', 'product_id')
            ->withPivot('quantity');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'package_id');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

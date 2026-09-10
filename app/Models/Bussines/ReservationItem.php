<?php

namespace App\Models\Bussines;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationItem extends Model
{
    use HasFactory;

    protected $table = 'reservation_items';

    protected $fillable = [
        'reservation_id',
        'product_id',
        'quantity',
        'unit_price',
        'attributes_selected',
    ];

    protected $casts = [
        'attributes_selected' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

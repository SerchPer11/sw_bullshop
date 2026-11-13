<?php

namespace App\Models\Bussiness;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, hasUlids, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'event_id',
        'customer_name',
        'customer_email',
        'customer_timezone',
        'customer_phone',
        'order_status',
        'payment_status',
        'payment_method',
        'stripe_payment_id',
        'subtotal',
        'coupon_id',
        'discount_amount',
        'street',
        'outdoor_number',
        'indoor_number',
        'colony',
        'city',
        'state',
        'postal_code',
        'shipping_cost',
        'total_amount',
        'notes',
        'is_gift',
        'gift_message',
        'confirmation_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'confirmation_sent_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}

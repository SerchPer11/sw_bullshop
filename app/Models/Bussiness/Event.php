<?php

namespace App\Models\Bussiness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'event_date',
        'Order_deadline',
        'location',
        'is_active',
    ];

    public function Orders()
    {
        return $this->hasMany(Order::class, 'event_id');
    }
}

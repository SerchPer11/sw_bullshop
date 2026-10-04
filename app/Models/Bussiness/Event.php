<?php

namespace App\Models\Bussiness;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Bussiness\Reservation;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'event_date',
        'reservation_deadline',
        'location',
        'is_active',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'event_id');
    }
}

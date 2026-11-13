<?php

namespace App\Models\Bussiness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'minimum_purchase',
        'start_date',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Determina si el cupón ya alcanzó su límite de usos.
     * Si usage_limit es null o 0, el cupón tiene usos ilimitados.
     */
    public function hasReachedUsageLimit(): bool
    {
        return $this->usage_limit !== null
            && (int) $this->usage_limit > 0
            && (int) $this->used_count >= (int) $this->usage_limit;
    }

    /**
     * Libera un uso del cupón (cuando se cancela o reembolsa una orden) sin bajar de 0.
     */
    public function releaseUsage(): void
    {
        if ((int) $this->used_count > 0) {
            $this->decrement('used_count');
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_id');
    }
}

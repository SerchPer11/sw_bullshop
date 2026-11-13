<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use App\Models\Bussiness\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'cart_total' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (! $coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupón inválido o expirado',
            ], 400);
        }

        if ($coupon->hasReachedUsageLimit()) {
            return response()->json([
                'success' => false,
                'message' => 'Este cupón ha alcanzado su límite máximo de usos',
            ], 400);
        }

        if ($request->cart_total < $coupon->minimum_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'El mínimo de compra es de $'.number_format($coupon->minimum_purchase, 2),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cupón aplicado',
            'id' => $coupon->id,
            'discount' => $coupon->discount_value,
            'discount_type' => $coupon->discount_type,
            'code' => $coupon->code,
        ]);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnlineSalesEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('app.online_sales_enabled')) {
            if ($request->expectsJson()  && ! $request->inertia()) {
                return response()->json([
                    'message' => 'Las ventas en línea están pausadas temporalmente.',
                ], 503);
            }
            return redirect()->back()
                ->with('error', 'Ventas pausadas. !Muy pronto tendremos envíos a todo México!');
        }

        return $next($request);
    }
}

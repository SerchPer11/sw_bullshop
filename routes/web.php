<?php
use App\Http\Controllers\Bussiness\OrderTrackingController;
use App\Http\Controllers\Bussiness\POSController;
use App\Http\Controllers\Landing\CollectionController;
use App\Http\Controllers\Survey\PreRegisterController;
use App\Http\Controllers\Bussiness\CheckOutController;
use App\Http\Controllers\Bussiness\PackageController;
use App\Http\Controllers\Bussiness\ProductController;
use App\Http\Controllers\Bussiness\WebhookController;
use App\Http\Controllers\Bussiness\CouponController;
use App\Http\Controllers\Landing\LobbyController;
use App\Http\Controllers\Legal\LegalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


// Public routes

// LOBBY
Route::get('/', [LobbyController::class, 'index'])->name('lobby');
Route::get('/lobby', [LobbyController::class, 'index'])->name('lobby');
Route::get('/coleccion', [CollectionController::class, 'index'])->name('collection.index');
// Route::get('/apartados', [PopupController::class, 'index'])->name('popups');
// packages
Route::get('/paquete/{slug}', [PackageController::class, 'show'])->name('package.show');
// products
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');

// CHECKOUT
Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckOutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order_id}', [CheckOutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/stripe/success', [CheckOutController::class, 'stripeSuccess'])->name('checkout.stripe.success');
Route::get('/checkout/stripe/cancel/{order_id}', [CheckOutController::class, 'stripeCancel'])->name('checkout.stripe.cancel');
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);
// Cupons
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');

// Order tacking
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('order.track');
Route::post('/track-order', [OrderTrackingController::class, 'search'])->name('order.search');
Route::get('/track-order/{orderNumber}', [OrderTrackingController::class, 'showCustomer'])
    ->middleware('signed')
    ->name('order.customer.show');
Route::get('/my-orders', [OrderTrackingController::class, 'index'])
    ->middleware('auth')
    ->name('order.my-orders');
// Surveys
Route::get('/encuesta', [PreRegisterController::class, 'create'])
    ->middleware('throttle:60,1') // Limita a 60 visitas por minuto
    ->name('survey.create');
Route::post('/encuesta/store', [PreRegisterController::class, 'store'])
    ->middleware('throttle:100,1') // Limita a 3 envíos por minuto
    ->name('preregistration.store');

Route::get('/ecard/descargar', function () {
    $files = File::files(public_path('Images/Ecards'));

    if (empty($files)) {
        abort(404, 'Las e-cards aún no están listas.');
    }

    $randomFile = $files[array_rand($files)];

    return redirect(asset('Images/Ecards/'.$randomFile->getFilename()));
})->name('ecard.download')
    ->middleware('throttle:5,1'); // Limita a 5 descargas por minuto

/*
Route::redirect('/', 'lobby', 301);

Route::fallback(function () {
    return redirect('/encuesta');
});
*/

// POLITICAS
Route::get('/legal/aviso-de-privacidad', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/legal/terminos-y-condiciones', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/legal/devoluciones', [LegalController::class, 'refunds'])->name('legal.refunds');
Route::get('/legal/envios', [LegalController::class, 'shipping'])->name('legal.shipping');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Staff order operations
    Route::get('/staff/orders', [OrderTrackingController::class, 'indexStaff'])->name('order.staff.index');
    Route::get('/staff/orders/{orderNumber}', [OrderTrackingController::class, 'show'])->name('order.staff.show');
    Route::patch('/staff/orders/{orderNumber}/payment', [OrderTrackingController::class, 'markPayment'])->name('order.staff.payment');
    Route::patch('/staff/orders/{orderNumber}/delivery', [OrderTrackingController::class, 'markDelivery'])->name('order.staff.delivery');

    // Admin order cancellation and refund operations
    Route::post('/admin/orders/{order}/refund', [OrderTrackingController::class, 'refund'])->name('admin.orders.refund');
    Route::post('/admin/orders/{order}/cancel-cash', [OrderTrackingController::class, 'cancelCash'])->name('admin.orders.cancel-cash');

    // Mini Punto de Venta (POS) Pop-Up
    Route::get('/admin/pos', [POSController::class, 'index'])->name('admin.pos.index');
    Route::post('/admin/pos/checkout', [POSController::class, 'store'])->name('admin.pos.store');
});

require __DIR__.'/auth.php';

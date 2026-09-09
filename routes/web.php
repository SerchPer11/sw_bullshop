<?php

use App\Http\Controllers\Landing\CollectionController;
use App\Http\Controllers\Landing\PopupController;
use App\Http\Controllers\Landing\LobbyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LobbyController::class, 'index'])->name('lobby');

Route:: get('/coleccion', [CollectionController::class, 'index'])->name('collection');

Route::get('/apartados', [PopupController::class, 'index'])->name('popups');

/*Route::get('/tienda', function () {
    return Inertia::render('Shop/Index');
})->name('shop');*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

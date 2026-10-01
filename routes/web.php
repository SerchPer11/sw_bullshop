<?php

use App\Http\Controllers\Bussines\PackageController;
use App\Http\Controllers\Landing\CollectionController;
use App\Http\Controllers\Landing\LobbyController;
// use App\Http\Controllers\Landing\PopupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Survey\PreRegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes

// Lobby
/*
Route::get('/', [LobbyController::class, 'index'])->name('lobby');
Route::get('/coleccion', [CollectionController::class, 'index'])->name('collection');
// Route::get('/apartados', [PopupController::class, 'index'])->name('popups');

// packages
Route::get('/paquete/{slug}', [PackageController::class, 'show'])->name('package.show');
*/
// Surveys
Route::get('/encuesta', [PreRegisterController::class, 'create'])

    ->name('survey.create'); // Limita a 60 visitas por minuto
Route::post('/encuesta/store', [PreRegisterController::class, 'store'])
     // Limita a 3 envíos por minuto
    ->name('preregistration.store');

Route::redirect('/', '/encuesta');

Route::fallback(function () {
    return redirect('/encuesta');
});

/*
/*Route::get('/tienda', function () {
    return Inertia::render('Shop/Index');
})->name('shop');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

require __DIR__.'/auth.php';

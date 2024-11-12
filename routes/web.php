<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Carbon\Carbon;

Route::get('/', function () {
    $fechaLimite = Carbon::now()->subYear(18)->format('Y-m-d');
    return Inertia::render('Welcome', compact('fechaLimite'));
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([ "prefix" => 'v1' ], function() {
    Route::post('persona/registro', [\App\Http\Controllers\CtcPersonaController::class, 'store'])
        ->name('persona.store');
});

require __DIR__.'/auth.php';

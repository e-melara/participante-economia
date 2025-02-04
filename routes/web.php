<?php


use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    $fechaLimite = Carbon::now()->subYear(18)->format('Y-m-d');
    return Inertia::render('Welcome', compact('fechaLimite'));
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [ DashboardController::class, 'home' ])->name('dashboard');
    Route::get('/dashboard/participantes', [ DashboardController::class, 'users' ])->name('dashboard.admin');

    Route::get('/participantes', [ DashboardController::class, 'usersPagination' ])->name('participantes.pagination');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([ "prefix" => 'v1' ], function() {
    Route::post('persona/registro', [\App\Http\Controllers\CtcPersonaController::class, 'store'])
        ->name('persona.store');

    Route::post('persona/registro/validar', [\App\Http\Controllers\CtcPersonaController::class, 'validar'])
      ->name('persona.validar');

    Route::post('persona/registro/token', [\App\Http\Controllers\CtcPersonaController::class, 'generateToken'])
      ->name('persona.token');
});

require __DIR__.'/auth.php';

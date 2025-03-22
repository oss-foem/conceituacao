<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Página inicial do app de login
Route::get('/', function () {
    return redirect('login');
});

/* 
    Geradas com o Breeze 
*/
// Rota para a página inicial
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* 
    Rotas para profiles
*/
Route::middleware('auth')->group(function () {
    Route::get('/profiles', function () {
        return view('profiles.home');
    })->name('profiles.home');

    Route::prefix('profiles')->group(function () {
        Route::get('/list', [ProfileController::class, 'index'])->name('profiles.index');
        Route::get('/create', [ProfileController::class, 'create'])->name('profiles.create');
        Route::post('/store', [ProfileController::class, 'store'])->name('profiles.store');
        Route::get('/edit/{profile}', [ProfileController::class, 'edit'])->name('profiles.edit');
        Route::put('/update/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
        Route::delete('/destroy/{profile}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
    });
});

/* 
    Rotas para users
*/
Route::get('/users', function () {
    return view('users.home');
})->middleware('auth')->name('users.home');

Route::prefix('users')->group(function () {
    Route::get('/list', [UserController::class, 'index'])->middleware('auth')->name('users.index'); 
    Route::get('/create', [UserController::class, 'create'])->name('users.create'); 
    Route::post('/store', [UserController::class, 'store'])->name('users.store'); 
    Route::get('/edit/{user}', [UserController::class, 'edit'])->middleware('auth')->name('users.edit'); 
    Route::put('/update/{user}', [UserController::class, 'update'])->middleware('auth')->name('users.update'); 
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->middleware('auth')->name('users.destroy'); 
});

require __DIR__.'/auth.php';
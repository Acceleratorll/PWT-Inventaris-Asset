<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('/assets', AssetController::class);
    Route::resource('/types', AssetTypeController::class);
    Route::resource('/movements', MovementController::class);
    Route::resource('/rooms', RoomController::class);

    Route::prefix('/table')->name('table.')->group(function () {
        Route::get('/assets', [AssetController::class, 'tableAll'])->name('assets');
        Route::get('/types', [AssetController::class, 'tableAll'])->name('types');
        Route::get('/rooms', [RoomController::class, 'tableAll'])->name('rooms');
        Route::get('/movements', [MovementController::class, 'tableAll'])->name('movements');
    });

    Route::prefix('/select')->name('select.')->group(function () {
        Route::get('/rooms', [RoomController::class, 'selectAll'])->name('rooms');
        Route::get('/assets', [AssetController::class, 'selectAll'])->name('assets');
        Route::get('/types', [AssetTypeController::class, 'selectAll'])->name('types');
        Route::get('/movements', [MovementController::class, 'selectAll'])->name('movements');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

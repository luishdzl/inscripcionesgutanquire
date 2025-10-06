<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepresentadoController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return app()->make(AdminController::class)->dashboard();
        }
        return view('dashboard');
    })->name('dashboard');

    // Perfil de representante
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Representados - rutas para USUARIOS NORMALES
    Route::middleware(['role:user'])->group(function () {
        Route::resource('representados', RepresentadoController::class)->except(['show']);
    });

    // Representados - rutas para ADMINISTRADORES
    Route::middleware(['role:user'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Los administradores pueden ver, editar y eliminar todos los representados
        Route::get('/representados', [RepresentadoController::class, 'index'])->name('representados.index');
        Route::get('/representados/{representado}/edit', [RepresentadoController::class, 'edit'])->name('representados.edit');
        Route::put('/representados/{representado}', [RepresentadoController::class, 'update'])->name('representados.update');
        Route::delete('/representados/{representado}', [RepresentadoController::class, 'destroy'])->name('representados.destroy');
    });
});
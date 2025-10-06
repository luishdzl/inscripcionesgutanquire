<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepresentadoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::middleware(['auth'])->group(function () {
    // Dashboard principal - CORREGIDO
    Route::get('/dashboard', function (Request $request) {
        if (auth()->user()->role === 'admin') {
            return app()->make(AdminController::class)->dashboard($request);
        }
        return view('dashboard');
    })->name('dashboard');

    // Perfil de representante
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Rutas para ADMINISTRADORES
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Gestión de usuarios
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Los administradores pueden gestionar todos los representados
        Route::get('/admin/representados', [RepresentadoController::class, 'adminIndex'])->name('admin.representados.index');
    });

    // Representados - rutas para USUARIOS NORMALES
    Route::middleware(['role:user'])->group(function () {
        Route::resource('representados', RepresentadoController::class)->except(['show']);
    });

    // Representados - rutas compartidas (tanto admin como user pueden editar/eliminar)
    Route::middleware(['auth'])->group(function () {
        Route::get('/representados/{representado}/edit', [RepresentadoController::class, 'edit'])->name('representados.edit');
        Route::put('/representados/{representado}', [RepresentadoController::class, 'update'])->name('representados.update');
        Route::delete('/representados/{representado}', [RepresentadoController::class, 'destroy'])->name('representados.destroy');
    });
});
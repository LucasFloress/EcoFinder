<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\AdminManagementController;
use App\Http\Controllers\SuperAdmin\PuntoVerdeController as SuperAdminPuntoVerdeController;
use App\Http\Controllers\Admin\PuntoVerdeController as AdminPuntoVerdeController;
use App\Http\Controllers\User\SolicitudController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SUPER ADMIN
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
    Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminManagementController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
    Route::patch('/admins/{admin}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('admins.toggle-status');
    
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::get('/puntos-verdes', [SuperAdminPuntoVerdeController::class, 'index'])->name('puntos-verdes.index');
    Route::post('/puntos-verdes', [SuperAdminPuntoVerdeController::class, 'store'])->name('puntos-verdes.store');
    Route::put('/puntos-verdes/{puntoVerde}', [SuperAdminPuntoVerdeController::class, 'update'])->name('puntos-verdes.update');
    Route::delete('/puntos-verdes/{puntoVerde}', [SuperAdminPuntoVerdeController::class, 'destroy'])->name('puntos-verdes.destroy');

    Route::get('/settings', function () {
        return view('super-admin.settings');
    })->name('settings');
});

// ADMIN
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/puntos-verdes', [AdminPuntoVerdeController::class, 'index'])->name('puntos-verdes.index');
    Route::post('/puntos-verdes', [AdminPuntoVerdeController::class, 'store'])->name('puntos-verdes.store');
    Route::put('/puntos-verdes/{puntoVerde}', [AdminPuntoVerdeController::class, 'update'])->name('puntos-verdes.update');
    Route::delete('/puntos-verdes/{puntoVerde}', [AdminPuntoVerdeController::class, 'destroy'])->name('puntos-verdes.destroy');
    
    Route::get('/users', function () {
        $users = \App\Models\User::all();
        return view('admin.users.index', compact('users'));
    })->name('users.index');
    
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');
});

// USUARIOS (VECINOS Y EMPRENDEDORES)
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
    Route::get('solicitudes/{solicitud}/edit', [SolicitudController::class, 'edit'])->name('solicitudes.edit');
    Route::put('solicitudes/{solicitud}', [SolicitudController::class, 'update'])->name('solicitudes.update');
    Route::delete('solicitudes/{solicitud}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy');
    
    Route::patch('solicitudes/{solicitud}/cancelar', [SolicitudController::class, 'cancelar'])->name('solicitudes.cancelar');
    Route::patch('solicitudes/{solicitud}/completar', [SolicitudController::class, 'completar'])->name('solicitudes.completar');
    
    Route::post('solicitudes/{solicitud}/suscribirse', [SolicitudController::class, 'suscribirse'])->name('solicitudes.suscribirse');
    
    Route::get('mis-transacciones', [SolicitudController::class, 'misTransacciones'])->name('mis-transacciones');
    Route::patch('transacciones/{transaccion}/cancelar', [SolicitudController::class, 'cancelarTransaccion'])->name('transacciones.cancelar');
    Route::patch('transacciones/{transaccion}/entregar', [SolicitudController::class, 'marcarEntregada'])->name('transacciones.entregar');
    
    Route::get('offers', function () {
        return view('user.offers.index');
    })->name('offers.index');
    
    Route::get('favorites', function () {
        return view('user.favorites');
    })->name('favorites');
});

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

require __DIR__.'/auth.php';
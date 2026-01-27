<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

// ====================================
// RUTAS PÚBLICAS (SIN MIDDLEWARE)
// ====================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================================
// RUTAS PROTEGIDAS (CON MIDDLEWARE)
// ====================================
Route::middleware(['check.client'])->group(function () {
    
    // Redirección raíz
    Route::get('/', function () {
        return redirect('/dashboard/home');
    });

    // Redirección dashboard
    Route::get('/dashboard', function () {
        return redirect('/dashboard/home');
    });

    // Ruta ask
    Route::post('/ask', [HomeController::class, 'ask']);

    // Grupo de rutas del dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('dashboard.home');
        Route::get('/home2', [HomeController::class, 'home2'])->name('dashboard.home2');
        Route::get('/informes', [HomeController::class, 'informes'])->name('dashboard.informes');
        Route::get('/comparacion', [HomeController::class, 'comparacion'])->name('dashboard.comparacion');
        Route::get('/seguimiento', [HomeController::class, 'seguimiento'])->name('dashboard.seguimiento');
        
        // Rutas POST
        Route::post('/getPreInforme', [HomeController::class, 'getPreInforme']);
        Route::post('/getPreInformeExtra', [HomeController::class, 'getPreInformeExtra']);
        Route::post('/getInforme', [HomeController::class, 'getInforme']);
        Route::post('/getPunto', [HomeController::class, 'getPunto']);
        Route::post('/getComparar', [HomeController::class, 'getComparar']);
        Route::post('/getbuscarFolio', [HomeController::class, 'getbuscarFolio']);
        Route::post('/getSeguimiento', [HomeController::class, 'getSeguimiento']);
    });
});
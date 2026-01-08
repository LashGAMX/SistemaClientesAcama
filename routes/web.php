<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/ask', [HomeController::class, 'ask']);
    Route::get('/dashboard', function () {
        if (!session()->has('Id_cliente')) {
            return redirect('/login');
        }
         return redirect('/dashboard/home');
    });
    Route::get('/', function () { 
        if (!session()->has('Id_cliente')) {
            return redirect('/login');
        }
         return redirect('/dashboard/home');
    });
    Route::group(['prefix' => '/dashboard'], function () { 
        Route::get('/home', [HomeController::class, 'index']);
        Route::get('/home2', [HomeController::class, 'home2']);
        Route::get('/informes', [HomeController::class, 'informes']);
        Route::get('/comparacion', [HomeController::class, 'comparacion']);
        Route::get('/seguimiento', [HomeController::class, 'seguimiento']);
        Route::post('/getPreInforme', [HomeController::class, 'getPreInforme']);
        Route::post('/getInforme', [HomeController::class, 'getInforme']);
        Route::post('/getPunto', [HomeController::class, 'getPunto']);
        Route::post('/getComparar', [HomeController::class, 'getComparar']);
        Route::post('/getbuscarFolio', [HomeController::class, 'getbuscarFolio']); 
        Route::post('/getSeguimiento', [HomeController::class, 'getSeguimiento']); 
        
    });  
    
});

  
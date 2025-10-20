<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocenteController;

// Ruta de prueba
Route::get('/test', function() {
    return response()->json(['message' => 'API funcionando']);
});

// Login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-google', [AuthController::class, 'loginWithGoogle']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas de coordinacion
    Route::prefix('coordinacion')->group(function(){

        // Rutas de gestión de docentes
        Route::post('guardar-docente', [DocenteController::class, 'guardarDocente']);
        Route::get('lista-docente', [DocenteController::class, 'listaDocentes']);
        Route::get('docente/{id}', [DocenteController::class, 'show']);
        Route::put('docente-editar/{id}', [DocenteController::class, 'update']);
        Route::delete('docente/eliminar/{id}', [DocenteController::class, 'eliminarDocente']);
        Route::put('docente/restaurar/{id}', [DocenteController::class, 'restaurarDocente']);

    });
    Route::get('/user', function(Request $request){
        return $request->user();
    });
});

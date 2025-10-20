<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocenteController;
use App\Http\Controllers\Api\AlumnoController;
use App\Models\Grupo;

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

        // Rutas de gestión de alumnos
        Route::get('formulario-alumno', function () {
            $grupos = Grupo::with(['carrera', 'cuatrimestre'])->orderBy('created_at', 'desc')->get();

            return response()->json(['data' => $grupos], 200);
        });
        Route::post('alumno/guardar', [AlumnoController::class, 'guardarAlumno']);
        Route::get('lista-alumnos', [AlumnoController::class, 'listaAlumnos']);
        Route::delete('alumno/eliminar/{id}', [AlumnoController::class, 'eliminarAlumno']);
        Route::put('alumno/restaurar/{id}', [AlumnoController::class, 'restaurarAlumno']);

    });
    Route::get('/user', function(Request $request){
        return $request->user();
    });
});

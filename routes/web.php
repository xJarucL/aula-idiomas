<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CoordinadorController;
use App\Models\Grupo;



Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/iniciando_sesion', [UserController::class, 'login'])
    ->middleware('guest')
    ->name('iniciando');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::prefix('alumno')->group(function(){
    Route::get('/inicio', function () {
        return view('alumno.inicio');
    })->name('alumno.inicio');
});

Route::prefix('docente')->group(function(){
    Route::get('/inicio', function () {
        return view('docente.inicio');
    })->name('docente.inicio');
});

Route::prefix('coordinacion')->group(function(){
    Route::get('/inicio', [CoordinadorController::class, 'inicio'])->name('coordinacion.inicio');

    Route::get('/lista-alumnos', [AlumnoController::class, 'listaAlumnos'])->name('coordinacion.lista-alumnos');

    Route::get('/lista-docente', [DocenteController::class, 'listaDocentes'])->name('coordinacion.lista-docente');

    Route::get('/lista-grupos', [CoordinadorController::class, 'listaGrupos'])->name('coordinacion.lista-grupos');

    Route::get('/registro-alumno', function () {
        $grupos = Grupo::with(['carrera', 'cuatrimestre'])->get();
        return view('coordinacion.registro-alumno', compact('grupos'));
    })->name('coordinacion.registro-alumno');
    Route::post('/guardar-alumno', [AlumnoController::class, 'store'])->name('coordinacion.guardar-alumno');

    Route::get('/registro-docente', function () {
        return view('coordinacion.registro-docente');
    })->name('coordinacion.registro-docente');
    Route::post('/guardar-docente', [CoordinadorController::class, 'store'])->name('coordinacion.guardar-docente');

    Route::get('/registro-grupo', function () {
        return view('coordinacion.registro-grupo');
    })->name('coordinacion.registro-grupo');
});

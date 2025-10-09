<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CoordinadorController;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Cuatrimestre;



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

    Route::get('/perfil', function () {
        return view('alumno.perfil');
    })->name('alumno.perfil');

    Route::get('/editar-perfil', function () {
        return view('alumno.editar-perfil');
    })->name('alumno.editar-perfil');
});

Route::prefix('docente')->group(function(){
    Route::get('/inicio', function () {
        return view('docente.inicio');
    })->name('docente.inicio');

    Route::get('/perfil', function () {
        return view('docente.perfil');
    })->name('docente.perfil');

    Route::get('/editar-perfil', function () {
        return view('docente.editar-perfil');
    })->name('docente.editar-perfil');
});

Route::prefix('coordinacion')->group(function(){
    Route::get('/inicio', [CoordinadorController::class, 'inicio'])->name('coordinacion.inicio');

    Route::get('/lista-alumnos', [AlumnoController::class, 'listaAlumnos'])->name('coordinacion.lista-alumnos');

    Route::get('/lista-docente', [DocenteController::class, 'listaDocentes'])->name('coordinacion.lista-docente');

    Route::get('/lista-grupos', [CoordinadorController::class, 'listaGrupos'])->name('coordinacion.lista-grupos');

    Route::get('/lista-coordinador', [CoordinadorController::class, 'listaCoordinadores'])->name('coordinacion.lista-coordinador');

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
        $carreras = Carrera::all();
        $cuatrimestres = Cuatrimestre::all();
        return view('coordinacion.registro-grupo', compact('carreras', 'cuatrimestres'));
    })->name('coordinacion.registro-grupo');
    Route::post('/guardar-grupo', [CoordinadorController::class, 'guardarGrupo'])->name('coordinacion.guardar-grupo');

    Route::get('/registro-coordinador', function () {
        return view('coordinacion.registro-coordinador');
    })->name('coordinacion.registro-coordinador');
    Route::post('/guardar-coordinador', [CoordinadorController::class, 'guardarCoordinador'])->name('coordinacion.guardar-coordinador');

    Route::get('/perfil', function () {
        return view('coordinacion.perfil');
    })->name('coordinacion.perfil');

    Route::get('/editar-perfil', function () {
        return view('coordinacion.editar-perfil');
    })->name('coordinacion.editar-perfil');
});

Route::get('/recuperar-contrasena', function (){
    return view('./reset/recuperar-contrasena');
})->name('recuperar-contrasena');

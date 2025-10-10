<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\GrupoController;
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

    // RUTAS DE ALUMNOS
    Route::get('/registro-alumno', function () {
        $grupos = Grupo::with(['carrera', 'cuatrimestre'])->get();
        return view('coordinacion.registro-alumno', compact('grupos'));
    })->name('coordinacion.registro-alumno');
    Route::post('/guardar-alumno', [AlumnoController::class, 'store'])->name('coordinacion.guardar-alumno');
    Route::get('/lista-alumnos', [AlumnoController::class, 'listaAlumnos'])->name('coordinacion.lista-alumnos');
    Route::get('/lista-alumnos/deshabilitados', [AlumnoController::class, 'listaAlumnosDeshabilitados'])->name('coordinacion.lista-alumnos-deshabilitados');
    Route::delete('/alumno/eliminar/{id}', [AlumnoController::class, 'eliminarAlumno'])->name('alumno.eliminar');
    Route::post('/alumno/restaurar/{id}', [AlumnoController::class, 'restaurarAlumno'])->name('alumno.restaurar');

    // RUTAS DE DOCENTES
    Route::get('/lista-docente', [DocenteController::class, 'listaDocentes'])->name('coordinacion.lista-docentes');
    Route::get('/lista-docente/deshabilitados', [DocenteController::class, 'listaDocentesDeshabilitados'])->name('coordinacion.lista-docentes-deshabilitados');
    Route::get('/registro-docente', function () {
        return view('coordinacion.registro-docente');
    })->name('coordinacion.registro-docente');
    Route::post('/guardar-docente', [DocenteController::class, 'store'])->name('coordinacion.guardar-docente');
    Route::delete('/docente/eliminar/{id}', [DocenteController::class, 'eliminarDocente'])->name('docente.eliminar');
    Route::post('/docente/restaurar/{id}', [DocenteController::class, 'restaurarDocente'])->name('docente.restaurar');


    // RUTAS DE GRUPOS
    Route::get('/lista-grupos', [GrupoController::class, 'listaGrupos'])->name('coordinacion.lista-grupos');
    Route::get('/lista-grupos/deshabilitados', [GrupoController::class, 'listaGruposDeshabilitados'])->name('coordinacion.lista-grupos-deshabilitados');
    Route::get('/registro-grupo', function () {
        $carreras = Carrera::all();
        $cuatrimestres = Cuatrimestre::all();
        return view('coordinacion.registro-grupo', compact('carreras', 'cuatrimestres'));
    })->name('coordinacion.registro-grupo');
    Route::post('/guardar-grupo', [GrupoController::class, 'guardarGrupo'])->name('coordinacion.guardar-grupo');
    Route::delete('/grupo/eliminar/{id}', [GrupoController::class, 'eliminarGrupo'])->name('grupo.eliminar');
    Route::post('/grupo/restaurar/{id}', [GrupoController::class, 'restaurarGrupo'])->name('grupo.restaurar');

    // RUTSA DE COORDINADOR
    Route::get('/lista-coordinador', [CoordinadorController::class, 'listaCoordinadores'])->name('coordinacion.lista-coordinador');
    Route::get('/lista-coordinador/deshabilitados', [CoordinadorController::class, 'listaCoordinadoresDeshabilitados'])->name('coordinacion.lista-coordinador-deshabilitados');
    Route::get('/registro-coordinador', function () {
        return view('coordinacion.registro-coordinador');
    })->name('coordinacion.registro-coordinador');
    Route::post('/guardar-coordinador', [CoordinadorController::class, 'guardarCoordinador'])->name('coordinacion.guardar-coordinador');
    Route::delete('/coordinador/eliminar/{id}', [CoordinadorController::class, 'eliminarCoordinador'])->name('coordinador.eliminar');
    Route::post('/coordinador/restaurar/{id}', [CoordinadorController::class, 'restaurarCoordinador'])->name('coordinador.restaurar');

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

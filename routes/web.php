<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;


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
    Route::get('/inicio', function () {
        return view('coordinacion.inicio');
    })->name('coordinacion.inicio');

    Route::get('/lista-alumnos', function () {
        return view('coordinacion.lista-alumnos');
    })->name('coordinacion.lista-alumnos');

    Route::get('/lista-docente', function () {
        return view('coordinacion.lista-docente');
    })->name('coordinacion.lista-docente');


    Route::get('/lista-grupos', function () {
        return view('coordinacion.lista-grupos');
    })->name('coordinacion.lista-grupos');

    Route::get('/registro-alumno', function () {
        return view('coordinacion.registro-alumno');
    })->name('coordinacion.registro-alumno');

    Route::get('/registro-docente', function () {
        return view('coordinacion.registro-docente');
    })->name('coordinacion.registro-docente');

    Route::get('/registro-grupo', function () {
        return view('coordinacion.registro-grupo');
    })->name('coordinacion.registro-grupo');
});

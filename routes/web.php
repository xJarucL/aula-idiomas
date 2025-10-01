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
});


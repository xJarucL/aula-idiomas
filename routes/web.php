<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/iniciando_sesion', [UserController::class, 'login'])
    ->middleware('guest')
    ->name('iniciando');



Route::prefix('alumno')->group(function(){
    Route::get('/alumno/inicio', function () {
        return view('alumno.inicio');
    })->name('alumno.inicio');
});

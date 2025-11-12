<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\RecuperacionController;
use App\Http\Controllers\ActividadController;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Cuatrimestre;
use App\Http\Middleware\RolMiddleware;


Route::get('/', function () {
    return view('login');
})->name('login')->middleware(\App\Http\Middleware\RedirectIfAuthenticated::class);

Route::post('/iniciando_sesion', [UserController::class, 'login'])
    ->middleware('guest')
    ->name('iniciando');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::prefix('alumno')
    ->middleware(['auth', RolMiddleware::class . ':1'])
    ->group(function(){

    Route::get('/inicio', [AlumnoController::class, 'cargarPanel'])->name('alumno.inicio');

    Route::get('/actividades', [AlumnoController::class, 'misActividades'])->name('alumno.lista-actividades');

    Route::get('/progreso', function () {
        return view('alumno.progreso');
    })->name('alumno.progreso');

    Route::get('/detalles-actividad/{id}', function () {
        return view('alumno.detalle-actividad');
    })->name('alumno.detalle-actividad');

    // RUTAS DE PERFIL
    Route::get('/perfil', [AlumnoController::class, 'perfilAlumno'])->name('alumno.perfil');
    Route::get('/editar-perfil', function () {
        return view('alumno.editar-perfil');
    })->name('alumno.editar');
    Route::post('/editando/perfil', [AlumnoController::class, 'actualizarPerfil'])->name('alumno.actualizar-perfil');
    Route::post('/editando/password', [AlumnoController::class, 'actualizarPassword'])->name('alumno.actualizar-password');

});

Route::prefix('docente')
    ->middleware(['auth', RolMiddleware::class . ':2'])
    ->group(function(){

    Route::get('/inicio', function () {
        return view('docente.inicio');
    })->name('docente.inicio');

    // RUTAS DE GRUPOS - DOCENTE
    Route::get('mis-grupos', [GrupoController::class, 'listaGruposDocente'])->name('docente.mis-grupos');
    Route::get('grupo/{id}', [GrupoController::class, 'detalleGrupo'])->name('docente.detalle-grupo');

    // RUTAS DE INFORMACIÓN DE ALUMNO - DOCENTE
    Route::get('alumno/{id}', [AlumnoController::class, 'detalleAlumno'])->name('docente.detalle-alumno');
    Route::get('alumno/{alumno}/grupo/{grupo}/actividades', [AlumnoController::class, 'actividadesGrupo'])->name('docente.actividades-alumno');


    // RUTAS DE PERFIL
    Route::get('/perfil', function () {
        return view('docente.perfil');
    })->name('docente.perfil');

    // Rutas de actividades
    Route::get('/crear-actividad', function (){
        return view('docente.crear-actividad');
    })->name('docente.crear-actividad');
    Route::post('actividad/guardar', [ActividadController::class, 'guardar'])->name('actividad.guardar');
    Route::get('lista-actividades', [ActividadController::class, 'listaActividadesDocente'])->name('docente.lista-actividades');
    Route::get('lista-actividades-deshabilitadas', [ActividadController::class, 'listaActividadesDocenteDeshabilitadas'])->name('docente.lista-actividades-deshabilitadas');
    Route::delete('actividad/eliminar/{id}', [ActividadController::class, 'eliminarActividad'])->name('actividad.eliminar');
    Route::post('actividad/restaurar/{id}', [ActividadController::class, 'restaurarActividad'])->name('actividad.restaurar');
    Route::get('actividad/{actividad}/alumno/{alumno}/respuestas', [ActividadController::class, 'verRespuestasActividadAlumno'])->name('actividad.respuestas');
    Route::get('actividad/{id}/asignar', [ActividadController::class, 'formAsignar'])->name('docente.asignar-actividad');
    Route::post('actividad/{id}/asignando', [ActividadController::class, 'asignarActividad'])->name('docente.asignando-actividad');

    Route::get('/editar/perfil', function () {
        return view('docente.editar-perfil');
    })->name('docente.editar');
    Route::put('/editando/perfil', [DocenteController::class, 'actualizarPerfil'])->name('docente.actualizar-perfil');
});

Route::prefix('coordinacion')
    ->middleware(['auth', RolMiddleware::class . ':3'])
    ->group(function(){

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
    Route::get('/detalle-alumno/{id}', [AlumnoController::class, 'cargarAlumno'])->name('coordinacion.alumno-detalle');
    Route::get('/editar-alumno/{id}', [AlumnoController::class, 'loadAlumno'])->name('coordinacion.alumno-editar');
    Route::put('/editando/alumno', [AlumnoController::class, 'editarAlumno'])->name('coordinacion.actualizar-alumno');

    // RUTAS DE DOCENTES
    Route::get('/lista-docente', [DocenteController::class, 'listaDocentes'])->name('coordinacion.lista-docentes');
    Route::get('/lista-docente/deshabilitados', [DocenteController::class, 'listaDocentesDeshabilitados'])->name('coordinacion.lista-docentes-deshabilitados');
    Route::get('/registro-docente', function () {
        return view('coordinacion.registro-docente');
    })->name('coordinacion.registro-docente');
    Route::post('/guardar-docente', [DocenteController::class, 'store'])->name('coordinacion.guardar-docente');
    Route::delete('/docente/eliminar/{id}', [DocenteController::class, 'eliminarDocente'])->name('docente.eliminar');
    Route::post('/docente/restaurar/{id}', [DocenteController::class, 'restaurarDocente'])->name('docente.restaurar');
    Route::get('/detalle-docente/{id}', [DocenteController::class, 'detalleDocente'])->name('docente.detalle');
    Route::get('/editar-docente/{id}', [DocenteController::class, 'cargarDocente'])->name('docente.cargar');
    Route::put('/editando/docente', [DocenteController::class, 'actualizarCorreo'])->name('docente.actualizar-correo');


    Route::post('usuarios/cambiar-tipo', [UserController::class, 'cambiarTipo'])->name('usuarios.cambiarTipo');

    // RUTAS DE GRUPOS
    Route::get('/lista-grupos', [GrupoController::class, 'listaGrupos'])->name('coordinacion.lista-grupos');
    Route::get('/lista-grupos/deshabilitados', [GrupoController::class, 'listaGruposDeshabilitados'])->name('coordinacion.lista-grupos-deshabilitados');
    Route::get('/registro-grupo', [GrupoController::class, 'cargarRegistroGrupo'])->name('coordinacion.registro-grupo');
    Route::post('/guardar-grupo', [GrupoController::class, 'guardarGrupo'])->name('coordinacion.guardar-grupo');
    Route::delete('/grupo/eliminar/{id}', [GrupoController::class, 'eliminarGrupo'])->name('grupo.eliminar');
    Route::post('/grupo/restaurar/{id}', [GrupoController::class, 'restaurarGrupo'])->name('grupo.restaurar');
    Route::get('/asignar-grupo/{id}', [GrupoController::class, 'cargarAlumnos'])->name('coordinacion.asignar-grupo');
    Route::post('/asignando/grupo', [GrupoController::class, 'asignarGrupo'])->name('coordinacion.guardar-asignacion-grupo');

    // RUTAS DE COORDINADOR
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
    })->name('coordinador.perfil');
    Route::get('/editar/perfil', function () {
        return view('coordinacion.editar-perfil');
    })->name('coordinador.editar');
    Route::put('/editando/perfil', [CoordinadorController::class, 'actualizarPerfil'])->name('coordinador.actualizar-perfil');
    Route::get('/detalle-coordinador/{id}', [CoordinadorController::class, 'detalleCoordinador'])->name('coordinador.detalle');
    Route::get('/editar-coordinador/{id}', [CoordinadorController::class, 'cargarCoordinador'])->name('coordinador.cargar');
    Route::put('/editando/coordiandor', [CoordinadorController::class, 'actualizarCorreo'])->name('coordinador.actualizar-correo');

});

// RUTAS PARA RECUPERAR CONTRASEÑA
Route::get('/recuperar-contrasena', function (){
    return view('./reset/recuperar-contrasena');
})->name('recuperar-contrasena');
Route::post('/recuperar-password', [RecuperacionController::class, 'enviarSolicitud'])->name('recuperar.enviar');
Route::get('/recuperar-password/restablecer/{usuario}', [RecuperacionController::class, 'restablecer'])->name('recuperar.restablecer');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocenteController;
use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\ActividadController;
use App\Http\Controllers\Api\GrupoController;
use App\Http\Controllers\Api\MensajeController;
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
        Route::get('alumnos', [AlumnoController::class, 'obtenerAlumnos']);

        // Rutas de gestión de grupos
        Route::get('lista-grupos', [GrupoController::class, 'listaGruposCoordinador']);
        Route::put('grupo/{id}/deshabilitar', [GrupoController::class, 'deshabilitarGrupo']);
        Route::put('grupo/{id}/habilitar', [GrupoController::class, 'habilitarGrupo']);
        Route::get('form-grupo', [GrupoController::class, 'formGrupo']);
        Route::post('grupo/crear', [GrupoController::class, 'guardarGrupo']);
        Route::post('grupo/asignar', [GrupoController::class, 'asignarGrupoAlumno']);

    });

    // Rutas de alumnos
    Route::prefix('alumno')->group(function(){

        // Perfil de usuario
        Route::get('perfil/{id}', [AlumnoController::class, 'perfilAlumno']);

        // Grupos del alumno
        Route::get('inicio/{id}', [GrupoController::class, 'gruposActualesAlumno']);

        // Rutas de actividades
        Route::get('actividades/{id}', [ActividadController::class, 'misActividades']);
        Route::get('actividad/{id}', [ActividadController::class, 'cargarActividad']);
        Route::post('responder', [ActividadController::class, 'guardarRespuesta']);
        Route::get('entrega/{fkActividad}/{userId}', [ActividadController::class, 'detalleEntrega']);
        Route::get('actividades-grupo/{idGrupo}/{idUsuario}', [ActividadController::class, 'actividadesGrupo']);

    });

    // Rutas de docentes
    Route::prefix('docente')->group(function(){

        // Rutas de gestión de actividades
        Route::post('guardar-actividad-preguntas', [ActividadController::class, 'guardarActividadPreguntas']);
        Route::get('actividades', [ActividadController::class, 'listaActividadesDocente']);
        Route::put('actividad/habilitar/{id}', [ActividadController::class, 'habilitarActividad']);
        Route::delete('actividad/deshabilitar/{id}', [ActividadController::class, 'deshabilitarActividad']);

        // Rutas de grupos
        Route::get('grupos', [GrupoController::class, 'listaGruposDocente']);
        Route::get('detalle-grupo/{id}', [GrupoController::class, 'detalleGrupo']);

        // Rutas de actividades
        Route::post('asignar-actividad', [ActividadController::class, 'asignarActividad']);
        Route::get('grupos-actividad', [ActividadController::class, 'obtenerGrupos']);
        Route::get('detalle-actividad/{id}', [ActividadController::class, 'detalleActividad']);

        // Rutas de alumnos
        Route::get('detalle-alumno/{id}', [AlumnoController::class, 'detalleAlumno']);

    });

    Route::get('/user', function(Request $request){
        return $request->user();
    });

    Route::put('perfil-editar/{id}', [AuthController::class, 'update']);

    Route::prefix('chat')->group(function(){
        Route::post('/enviar-mensaje', [MensajeController::class, 'enviar']);
        Route::get('/historial/{id}', [MensajeController::class, 'obtenerChats']);
        Route::get('/mensajes/{id_propio}/contacto/{id_contacto}', [MensajeController::class, 'obtenerMensajesChat']);
    });

});

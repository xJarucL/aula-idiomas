<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\GrupoMateria;
use App\Models\Grupo;

class GrupoController extends Controller
{
    public function listaGruposDocente(Request $request){
        $id = $request->pk_docente ?? auth()->user()->pk_usuario;

        $query = GrupoMateria::with(['grupo.carrera', 'materia'])
            ->where('fk_docente', $id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Grupos del docente cargados correctamente",
            'data' => $query
        ]);
    }

    public function detalleGrupo($id){
        $grupo = Grupo::with([
            'carrera',
            'cuatrimestre',
            'alumnos.usuario',
            'actividades'
        ])->find($id);

        if (!$grupo) {
            return response()->json([
                'success' => false,
                'message' => 'Grupo no encontrado'
            ], 404);
        }

        $grupo->actividades = $grupo->actividades->map(function($actividad){
            return [
                'pk_actividad' => $actividad->pk_actividad,
                'nom_actividad' => $actividad->nom_actividad,
                'descripcion' => $actividad->descripcion,
                'tipo' => $actividad->tipo,
                'fecha_inicio' => $actividad->pivot->fecha_inicio,
                'fecha_fin' => $actividad->pivot->fecha_fin,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $grupo
        ]);
    }

}

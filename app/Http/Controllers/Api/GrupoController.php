<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\GrupoMateria;
use App\Models\Grupo;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function listaGruposCoordinador(Request $request){

        $query = Grupo::with('carrera')->withTrashed()->orderBy('created_at', 'desc');

        $grupos = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => "Grupos cargados correctamente",
            'data' => $grupos
        ]);
    }

    public function deshabilitarGrupo($id){
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Grupo deshabilitado correctamente'
        ]);
    }

    public function habilitarGrupo($id){
        $grupo = Grupo::withTrashed()->findOrFail($id);
        $grupo->restore();

        return response()->json([
            'success' => true,
            'message' => 'Grupo habilitado correctamente'
        ]);
    }

}

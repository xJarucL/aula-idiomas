<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Preguntas;
use App\Models\OpcionesPregunta;
use App\Models\Grupo;
use App\Models\ActividadGrupo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    public function guardarActividadPreguntas(Request $request)
    {
        $request->validate([
            'nom_actividad' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo_actividad' => 'required|string|in:preguntas,pdf,auditiva',
            'preguntas' => 'sometimes|array',
            'preguntas.*.tipo' => 'sometimes|string|in:abierta,opcion_multiple,verdadero_falso',
        ]);

        DB::beginTransaction();
        try {
            $actividad = Actividades::create([
                'cod_actividad' => 'ACT-' . Str::upper(Str::random(6)),
                'nom_actividad' => $request->nom_actividad,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo_actividad,
                'fk_docente' => $request->fk_usuario
            ]);

            if ($request->has('preguntas')) {
                foreach ($request->preguntas as $preg) {
                    $tipo = $preg['tipo'] ?? 'abierta';

                    $pregunta = Preguntas::create([
                        'fk_actividad' => $actividad->pk_actividad,
                        'pregunta' => $preg['titulo'] ?? 'Pregunta sin título',
                        'descripcion' => $preg['descripcion'] ?? null,
                        'tipo' => $tipo,
                    ]);

                    if ($tipo === 'opcion_multiple' && isset($preg['opciones'])) {
                        foreach ($preg['opciones'] as $letra => $texto) {
                            OpcionesPregunta::create([
                                'fk_pregunta' => $pregunta->pk_pregunta,
                                'texto_opcion' => $texto,
                                'es_correcta' => ($preg['respuesta_correcta'] ?? '') === $letra,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Actividad registrada correctamente.',
                'actividad' => $actividad
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar actividad.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function inferirTipoPregunta(array $preg){
        if (isset($preg['opcion_a']) && isset($preg['opcion_b'])) {
            return 'opcion_multiple';
        } elseif (isset($preg['respuesta_correcta']) && in_array($preg['respuesta_correcta'], ['Verdadero', 'Falso'])) {
            return 'verdadero_falso';
        } else {
            return 'abierta';
        }
    }

    private function guardarOpciones($pregunta, array $preg){
        foreach (['a', 'b', 'c', 'd'] as $letra) {
            if (!empty($preg["opcion_{$letra}"])) {
                OpcionesPregunta::create([
                    'fk_pregunta' => $pregunta->pk_pregunta,
                    'texto_opcion' => $preg["opcion_{$letra}"],
                    'es_correcta' => ($preg['respuesta_correcta'] ?? '') === strtoupper($letra),
                ]);
            }
        }
    }

    public function listaActividadesDocente(Request $request){
        try {
            $usuario = Auth::user();

            $query = Actividades::withTrashed()->where('fk_docente', $usuario->pk_usuario);

            if ($request->filled('search')) {
                $search = str_replace(' ', '', $request->input('search'));
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("REPLACE(CONCAT(cod_actividad, nom_actividad, descripcion, tipo), ' ', '') LIKE ?", ["%{$search}%"])
                      ->orWhere('cod_actividad', 'like', "%{$search}%")
                      ->orWhere('nom_actividad', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%")
                      ->orWhere('tipo', 'like', "%{$search}%");
                });
            }

            if ($request->filled('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            $actividades = $query->orderBy('created_at', 'desc')->paginate(10);

            $actividades->getCollection()->transform(function ($actividad) {
                $actividad->fecha_formateada = $actividad->created_at->format('d/m/Y');
                $actividad->is_active = $actividad->deleted_at === null;
                return $actividad;
            });

            return response()->json([
                'success' => true,
                'data' => $actividades->items(),
                'pagination' => [
                    'current_page' => $actividades->currentPage(),
                    'last_page' => $actividades->lastPage(),
                    'per_page' => $actividades->perPage(),
                    'total' => $actividades->total(),
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal...',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function habilitarActividad($id){
        try {
            $actividad = Actividades::withTrashed()->findOrFail($id);
            $actividad->restore();

            return response()->json([
                'success' => true,
                'message' => 'Actividad habilitada correctamente',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo habilitar la actividad',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function deshabilitarActividad($id){
        try {
            $actividad = Actividades::findOrFail($id);
            $actividad->delete();

            return response()->json([
                'success' => true,
                'message' => 'Actividad deshabilitada correctamente',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo deshabilitar la actividad',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function asignarActividad(Request $request){
        $request->validate([
            'fk_actividad' => 'required|exists:actividades,pk_actividad',
            'grupos' => 'required|array',
            'grupos.*' => 'exists:grupo,pk_grupo',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        foreach ($request->grupos as $grupoId) {
            ActividadGrupo::create([
                'fk_actividad' => $request->fk_actividad,
                'fk_grupo' => $grupoId,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Actividad asignada correctamente a los grupos.',
        ]);
    }

    public function obtenerGrupos(){
        $grupos = Grupo::select('pk_grupo', 'nombre', 'año')->get();

        return response()->json([
            'success' => true,
            'data' => $grupos
        ]);
    }
}

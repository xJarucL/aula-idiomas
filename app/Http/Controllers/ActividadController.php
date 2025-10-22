<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Preguntas;
use App\Models\OpcionesPregunta;
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
                'fk_docente' => Auth::user()->pk_usuario
            ]);

            if ($request->has('preguntas')) {
                foreach ($request->preguntas as $preg) {
                    $tipo = $preg['tipo'] ?? $this->inferirTipoPregunta($preg);

                    $pregunta = Preguntas::create([
                        'fk_actividad' => $actividad->pk_actividad,
                        'pregunta' => $preg['titulo'] ?? $preg['descripcion'] ?? 'Pregunta sin título',
                        'tipo' => $tipo,
                    ]);

                    if ($tipo === 'opcion_multiple') {
                        $this->guardarOpciones($pregunta, $preg);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Actividad registrada correctamente.',
                'ruta' => route('docente.lista-actividades'),
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

    private function inferirTipoPregunta(array $preg)
    {
        if (isset($preg['opcion_a']) && isset($preg['opcion_b'])) {
            return 'opcion_multiple';
        } elseif (isset($preg['respuesta_correcta']) && in_array($preg['respuesta_correcta'], ['Verdadero', 'Falso'])) {
            return 'verdadero_falso';
        } else {
            return 'abierta';
        }
    }

    private function guardarOpciones($pregunta, array $preg)
    {
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Preguntas;
use App\Models\Alumno;
use App\Models\OpcionesPregunta;
use App\Models\RespuestasAlumno;
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
                        'pregunta' => $preg['titulo'] ?? 'Pregunta sin título',
                        'descripcion' => $preg['descripcion'] ?? null,
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

    public function listaActividadesDocente(Request $request){
        try {
            $usuario = Auth::user();

            $query = Actividades::where('fk_docente', $usuario->pk_usuario);

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

            $actividades = $query->paginate(10);

            if ($request->ajax()) {
                return view('partials.tabla_actividades', compact('actividades'))->render();
            }

            return view('docente.lista-actividades', compact('actividades'));
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal...',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function listaActividadesDocenteDeshabilitadas(Request $request){
        try {
            $usuario = Auth::user();

            $query = Actividades::onlyTrashed()->where('fk_docente', $usuario->pk_usuario);

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

            $actividades = $query->paginate(10);

            if ($request->ajax()) {
                return view('partials.tabla_actividades', compact('actividades'))->render();
            }

            return view('docente.lista-actividades', compact('actividades'));
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal...',
                'error' => $th->getMessage(),
            ]);
        }
    }



    public function eliminarActividad($id){
        try {
            $actividad = Actividades::findOrFail($id);

            $actividad->delete();

            return redirect()
                ->route('docente.lista-actividades')
                ->with('success', 'Actividad deshabilitada correctamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al deshabilitar la actividad: ' . $e->getMessage());
        }
    }

    public function restaurarActividad($id){
        try {
            $actividad = Actividades::onlyTrashed()->findOrFail($id);

            $actividad->restore();

            return redirect()
                ->route('docente.lista-actividades-deshabilitadas')
                ->with('success', 'Actividad restaurada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al restaurar la actividad: ' . $e->getMessage());
        }
    }

    public function verRespuestasActividadAlumno($actividadId, $alumnoId){
        $actividad = Actividades::with(['preguntas.opciones'])->findOrFail($actividadId);

        $alumno = Alumno::find($alumnoId);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }

        $respuestas = RespuestasAlumno::where('fk_actividad', $actividadId)
            ->where('fk_alumno', $alumnoId)
            ->get();

        if ($respuestas->isEmpty()) {
            return back()->with('error', 'El alumno no tiene respuestas registradas para esta actividad.');
        }

        $respuestasMap = $respuestas->keyBy('fk_pregunta');

        $evaluacion = [];
        $correctas = 0;
        $totalPreguntas = $actividad->preguntas->count();

        foreach ($actividad->preguntas as $pregunta) {
            $opcionCorrecta = null;
            if ($pregunta->tipo === 'opcion_multiple') {
                $opcionCorrecta = $pregunta->opciones->firstWhere('es_correcta', true)?->texto_opcion;
            }

            $r = $respuestasMap->get($pregunta->pk_pregunta);
            $respuestaAlumno = $r->respuesta ?? null;

            $esCorrecta = false;
            if ($respuestaAlumno !== null && $opcionCorrecta !== null) {
                $esCorrecta = mb_strtolower(trim($respuestaAlumno)) === mb_strtolower(trim($opcionCorrecta));
            } elseif ($pregunta->tipo === 'abierta') {
                $esCorrecta = null;
            }

            if ($esCorrecta === true) {
                $correctas++;
            }

            $evaluacion[] = [
                'pregunta' => $pregunta->pregunta,
                'tipo' => $pregunta->tipo,
                'respuesta_alumno' => $respuestaAlumno ?? 'No respondida',
                'respuesta_correcta' => $opcionCorrecta ?? 'N/A',
                'es_correcta' => $esCorrecta,
                'respuesta_model' => $r,
            ];
        }

        $calificacion = $totalPreguntas > 0 ? round(($correctas / $totalPreguntas) * 100, 2) : 0;

        return view('docente.actividad-respuesta', [
            'actividad' => $actividad,
            'alumno' => $alumno,
            'evaluacion' => $evaluacion,
            'correctas' => $correctas,
            'totalPreguntas' => $totalPreguntas,
            'calificacion' => $calificacion,
        ]);
    }

}

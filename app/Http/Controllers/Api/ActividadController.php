<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Actividades;
use App\Models\Preguntas;
use App\Models\OpcionesPregunta;
use App\Models\Grupo;
use App\Models\ActividadGrupo;
use App\Models\RespuestasAlumno;
use App\Models\User;
use App\Models\Alumno;
use App\Models\GrupoAlumno;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $grupos = Grupo::with('carrera')->get();

        return response()->json([
            'success' => true,
            'data' => $grupos
        ]);
    }

    public function detalleActividad($id){
        $actividad = Actividades::with(['grupos.alumnos.usuario'])
            ->find($id);

        if (!$actividad) {
            return response()->json([
                'success' => false,
                'message' => 'Actividad no encontrada'
            ], 404);
        }

        if ($actividad->tipo !== 'preguntas') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se permiten actividades de tipo preguntas'
            ], 400);
        }

        $alumnos = collect();
        foreach ($actividad->grupos as $grupo) {
            $alumnos = $alumnos->merge($grupo->alumnos);
        }

        $respuestas = RespuestasAlumno::where('fk_actividad', $actividad->pk_actividad)
            ->with(['alumno.usuario', 'pregunta'])
            ->get();

        $respuestasAgrupadas = $respuestas->groupBy('fk_alumno');

        $entregas = [];
        $noEntregados = [];

        foreach ($alumnos as $alumno) {
            if ($respuestasAgrupadas->has($alumno->pk_alumno)) {
                $entregas[] = [
                    'pk_alumno' => $alumno->pk_alumno,
                    'nombre_completo' => trim(
                        "{$alumno->usuario->nombres} {$alumno->usuario->ap_paterno} {$alumno->usuario->ap_materno}"
                    ),
                    'respondio' => true,
                    'respuestas' => $respuestasAgrupadas[$alumno->pk_alumno]->map(function ($r) {
                        return [
                            'pk_respuesta' => $r->pk_respuesta,
                            'fk_pregunta' => $r->fk_pregunta,
                            'pregunta' => $r->pregunta ? $r->pregunta->pregunta : null,
                            'respuesta' => $r->respuesta,
                            'es_correcta' => $r->es_correcta,
                            'created_at' => $r->created_at,
                        ];
                    })->values(),
                ];
            } else {
                $noEntregados[] = [
                    'pk_alumno' => $alumno->pk_alumno,
                    'nombre_completo' => trim(
                        "{$alumno->usuario->nombres} {$alumno->usuario->ap_paterno} {$alumno->usuario->ap_materno}"
                    ),
                    'respondio' => false,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'actividad' => [
                    'pk_actividad' => $actividad->pk_actividad,
                    'nom_actividad' => $actividad->nom_actividad,
                    'tipo' => $actividad->tipo,
                ],
                'entregas' => $entregas,
                'no_entregados' => $noEntregados,
            ]
        ]);
    }

    public function misActividades($id){
        $usuario = User::where('fk_tipo_usuario', 1)->find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el usuario o no es un alumno.',
            ], 404);
        }

        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro del alumno.',
            ], 404);
        }

        $grupos = GrupoAlumno::withTrashed()->where('fk_alumno', $alumno->pk_alumno)->get();
        $ahora = now();

        $pendientes = [];
        $entregadas = [];
        $noEntregadas = [];

        foreach ($grupos as $grupoAlumno) {
            $actividades = DB::table('actividad_grupo')
                ->join('actividades', 'actividad_grupo.fk_actividad', '=', 'actividades.pk_actividad')
                ->where('actividad_grupo.fk_grupo', $grupoAlumno->fk_grupo)
                ->select(
                    'actividades.pk_actividad',
                    'actividades.nom_actividad',
                    'actividades.descripcion',
                    'actividad_grupo.fecha_inicio',
                    'actividad_grupo.fecha_fin',
                    'actividad_grupo.fk_grupo',
                    'actividades.tipo'
                )
                ->get();

            foreach ($actividades as $act) {
                $entregada = DB::table('respuestas_alumno')
                    ->where('fk_actividad', $act->pk_actividad)
                    ->where('fk_alumno', $alumno->pk_alumno)
                    ->exists();

                $fechaFin = Carbon::parse($act->fecha_fin);

                if ($entregada) {
                    $entregadas[] = $act;
                } elseif ($ahora->lessThan($fechaFin)) {
                    $pendientes[] = $act;
                } else {
                    $noEntregadas[] = $act;
                }
            }
        }

        $pendientes = collect($pendientes)->map(fn($a) => (array)$a)->values();
        $entregadas = collect($entregadas)->map(fn($a) => (array)$a)->values();
        $noEntregadas = collect($noEntregadas)->map(fn($a) => (array)$a)->values();

        return response()->json([
            'success' => true,
            'message' => 'Actividades del alumno obtenidas correctamente',
            'resumen' => [
                'pendientes' => count($pendientes),
                'entregadas' => count($entregadas),
                'no_entregadas' => count($noEntregadas),
            ],
            'pendientes' => $pendientes,
            'entregadas' => $entregadas,
            'no_entregadas' => $noEntregadas,
        ]);
    }

    public function cargarActividad($id){
        try {
            $actividad = Actividades::with(['preguntas.opciones'])
                ->where('pk_actividad', $id)
                ->first();

            if (!$actividad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Actividad no encontrada.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Actividad cargada correctamente.',
                'actividad' => $actividad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar la actividad.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function guardarRespuesta(Request $request){
        try {
            $validated = $request->validate([
                'fk_actividad' => 'required|exists:actividades,pk_actividad',
                'fk_alumno' => 'required|integer',
                'respuestas' => 'required|array',
                'respuestas.*.fk_pregunta' => 'required|exists:preguntas,pk_pregunta',
                'respuestas.*.respuesta' => 'nullable|string',
            ]);

            $alumno = Alumno::where('fk_usuario', $validated['fk_alumno'])->first();

            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró un alumno asociado a este usuario.',
                ], 404);
            }

            foreach ($validated['respuestas'] as $resp) {
                $pregunta = Preguntas::find($resp['fk_pregunta']);
                $esCorrecta = null;

                if ($pregunta->tipo === 'opcion_multiple') {
                    $opcionCorrecta = $pregunta->opciones()
                        ->where('es_correcta', 1)
                        ->first();

                    if ($opcionCorrecta) {
                        $esCorrecta = (trim(strtolower($opcionCorrecta->texto_opcion)) === trim(strtolower($resp['respuesta'])));
                    } else {
                        $esCorrecta = false;
                    }
                } else {
                    $esCorrecta = false;
                }

                RespuestasAlumno::create([
                    'fk_pregunta' => $resp['fk_pregunta'],
                    'fk_alumno' => $alumno->pk_alumno,
                    'fk_actividad' => $validated['fk_actividad'],
                    'respuesta' => $resp['respuesta'],
                    'es_correcta' => $esCorrecta,
                    'calificada' => $pregunta->tipo === 'abierta' ? false : true,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Respuestas guardadas correctamente.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar respuestas: '.$e->getMessage(),
            ], 500);
        }
    }

    public function detalleEntrega($fk_actividad, $fk_usuario){
        try {
            $alumno = Alumno::where('fk_usuario', $fk_usuario)->first();

            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró un alumno asociado a este usuario.'
                ], 404);
            }

            $respuestas = RespuestasAlumno::with('pregunta')
                ->where('fk_actividad', $fk_actividad)
                ->where('fk_alumno', $alumno->pk_alumno)
                ->get();

            if ($respuestas->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron respuestas para esta actividad.'
                ]);
            }

            $todasCalificadas = $respuestas->every(fn($r) => $r->calificada == true);

            $total = $respuestas->count();
            $correctas = $respuestas->where('es_correcta', true)->count();
            $calificacion = $total > 0 ? round(($correctas / $total) * 100, 2) : null;

            $detalle = [
                'calificacion' => $todasCalificadas ? $calificacion : null,
                'comentarios' => $todasCalificadas ? 'Todas las respuestas fueron calificadas.' : 'Pendiente de calificación.',
                'fecha_entrega' => $respuestas->first()->created_at->format('Y-m-d H:i'),
                'respuestas' => $respuestas,
                'todas_calificadas' => $todasCalificadas
            ];

            return response()->json([
                'success' => true,
                'data' => $detalle
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalle de entrega: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function actividadesGrupo($idGrupo, $idUsuario){
        $usuario = User::find($idUsuario);

        if (!$usuario || $usuario->fk_tipo_usuario != 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el usuario o no es un alumno.',
            ], 404);
        }

        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro del alumno.',
            ], 404);
        }

        $actividades = DB::table('actividad_grupo')
            ->join('actividades', 'actividad_grupo.fk_actividad', '=', 'actividades.pk_actividad')
            ->where('actividad_grupo.fk_grupo', $idGrupo)
            ->select(
                'actividades.pk_actividad',
                'actividades.nom_actividad',
                'actividades.descripcion',
                'actividades.tipo',
                'actividad_grupo.fecha_inicio',
                'actividad_grupo.fecha_fin',
                'actividad_grupo.fk_grupo'
            )
            ->get();

        $ahora = Carbon::now();
        $pendientes = [];
        $entregadas = [];
        $noEntregadas = [];

        foreach ($actividades as $act) {
            $entregada = DB::table('respuestas_alumno')
                ->where('fk_actividad', $act->pk_actividad)
                ->where('fk_alumno', $alumno->pk_alumno)
                ->exists();

            $fechaFin = Carbon::parse($act->fecha_fin);

            if ($entregada) {
                $entregadas[] = $act;
            } elseif ($ahora->lessThan($fechaFin)) {
                $pendientes[] = $act;
            } else {
                $noEntregadas[] = $act;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Actividades del grupo obtenidas correctamente',
            'data' => [
                'resumen' => [
                    'pendientes' => count($pendientes),
                    'entregadas' => count($entregadas),
                    'no_entregadas' => count($noEntregadas),
                ],
                'pendientes' => $pendientes,
                'entregadas' => $entregadas,
                'no_entregadas' => $noEntregadas,
            ],
        ]);
    }
}

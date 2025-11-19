<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\ActividadPDF;
use App\Models\ActividadAuditivaFrases;
use App\Models\Preguntas;
use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\OpcionesPregunta;
use App\Models\RespuestasAlumno;
use App\Models\ActividadGrupo;
use App\Models\EntregaPDFAlumno;
use App\Models\RespuestaAuditivaAlumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActividadController extends Controller{

    public function guardar(Request $request){
        $request->validate([
            'nom_actividad' => 'required|string|max:255',
            'descripcion'   => 'required|string',
            'tipo'          => 'required|in:preguntas,pdf,auditiva',
        ]);

        DB::beginTransaction();

        try {
            $codigo = 'ACT-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            $actividad = new Actividades();
            $actividad->cod_actividad = $codigo;
            $actividad->nom_actividad = $request->nom_actividad;
            $actividad->descripcion = $request->descripcion;
            $actividad->tipo = $request->tipo;
            $actividad->fk_docente = Auth::id();
            $actividad->save();

            if ($request->tipo === 'preguntas' && $request->has('preguntas')) {
                foreach ($request->preguntas as $preg) {
                    $pregunta = new Preguntas();
                    $pregunta->fk_actividad = $actividad->pk_actividad;
                    $pregunta->pregunta = $preg['pregunta'] ?? '';
                    $pregunta->descripcion = $preg['descripcion'] ?? '';
                    $pregunta->tipo = $preg['tipo'] ?? 'abierta';
                    $pregunta->save();

                    if ($pregunta->tipo === 'opcion_multiple' && isset($preg['opciones'])) {
                        foreach ($preg['opciones'] as $op) {
                            $opcion = new OpcionesPregunta();
                            $opcion->fk_pregunta = $pregunta->pk_pregunta;
                            $opcion->texto_opcion = $op['texto_opcion'] ?? '';
                            $opcion->es_correcta = isset($op['es_correcta']) ? 1 : 0;
                            $opcion->save();
                        }
                    }
                }
            }

            if ($request->tipo === 'pdf' && $request->hasFile('archivo_docente')) {
                $file = $request->file('archivo_docente');
                $nombre = time() . '_' . $file->getClientOriginalName();
                $ruta = $file->storeAs('actividades/pdf', $nombre, 'public');

                $pdf = new ActividadPdf();
                $pdf->fk_actividad = $actividad->pk_actividad;
                $pdf->archivo_docente = $ruta;
                $pdf->save();
            }

            if ($request->tipo === 'auditiva') {
                $auditiva = new ActividadAuditivaFrases();
                $auditiva->fk_actividad = $actividad->pk_actividad;
                $auditiva->texto_frase = $request->texto_frase ?? '';

                if ($request->hasFile('archivo_audio_docente')) {
                    $audio = $request->file('archivo_audio_docente');
                    $nombreAudio = time() . '_' . $audio->getClientOriginalName();
                    $rutaAudio = $audio->storeAs('actividades/auditiva', $nombreAudio, 'public');
                    $auditiva->archivo_audio_docente = $rutaAudio;
                }

                $auditiva->save();
            }

            DB::commit();
            return response()->json([
                'mensaje' => 'Actividad guardada correctamente.',
                'ruta' => route('docente.lista-actividades'),
                'class' => 'success'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'mensaje' => 'Ocurrió un error al guardar la actividad'. $e->getMessage(),
                'class' => 'error'
            ], 500);
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

    public function formAsignar($id){
        $actividad = Actividades::findOrFail($id);
        $docenteId = Auth::id();

        $grupos = Grupo::whereHas('grupoMaterias', function ($query) use ($docenteId) {
            $query->where('fk_docente', $docenteId);
        })->orderBy('nombre')->get();

        return view('docente.asignar-actividad', compact('actividad', 'grupos'));
    }

    public function asignarActividad(Request $request, $id){

        $request->validate([
            'grupos' => 'required|array|min:1',
            'grupos.*' => 'exists:grupo,pk_grupo',
            'fecha_entrega' => 'required|date|after_or_equal:today',
        ], [
            'grupos.required' => 'Debes seleccionar al menos un grupo.',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a hoy.',
        ]);

        $fechaInicio = Carbon::now();

        foreach ($request->grupos as $grupoId) {
            ActividadGrupo::create([
                'fk_actividad' => $id,
                'fk_grupo' => $grupoId,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $request->fecha_entrega,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Actividad asignada correctamente.',
            'ruta' => route('docente.lista-actividades')
        ]);
    }

    public function detalleActividadAlumno($id){
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado.');
        }

        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();

        if (!$alumno) {
            return redirect()->back()->with('error', 'No se encontró el registro del alumno.');
        }

        $actividad = DB::table('actividades')->where('pk_actividad', $id)->first();

        if (!$actividad) {
            return redirect()->back()->with('error', 'Actividad no encontrada.');
        }

        $preguntas = collect();
        $pdfs = collect();
        $audios = collect();

        switch ($actividad->tipo) {
            case 'preguntas':
                $preguntas = DB::table('respuestas_alumno as ra')
                    ->join('preguntas as p', 'ra.fk_pregunta', '=', 'p.pk_pregunta')
                    ->select(
                        'p.pregunta',
                        'p.tipo as tipo_pregunta',
                        'ra.respuesta',
                        'ra.es_correcta',
                        'ra.calificada'
                    )
                    ->where('ra.fk_actividad', $id)
                    ->where('ra.fk_alumno', $alumno->pk_alumno)
                    ->get();
                break;

            case 'pdf':
                $pdfs = DB::table('entrega_pdf_alumno')
                    ->where('fk_actividad', $id)
                    ->where('fk_alumno', $alumno->pk_alumno)
                    ->select(
                        'archivo_alumno as ruta_archivo',
                        DB::raw("SUBSTRING_INDEX(archivo_alumno, '/', -1) as nombre_archivo"),
                        'calificacion',
                        'observaciones as retroalimentacion'
                    )
                    ->get();
                break;

            case 'auditiva':
                $audios = DB::table('respuesta_auditiva_alumno')
                    ->where('fk_actividad', $id)
                    ->where('fk_alumno', $alumno->pk_alumno)
                    ->select(
                        'archivo_audio_alumno as ruta_archivo',
                        DB::raw("SUBSTRING_INDEX(archivo_audio_alumno, '/', -1) as nombre_archivo"),
                        'calificacion',
                        'observaciones as retroalimentacion'
                    )
                    ->get();
                break;

            default:
                return redirect()->back()->with('error', 'Tipo de actividad desconocido.');
        }

        return view('alumno.detalle-actividad', compact('actividad', 'preguntas', 'pdfs', 'audios'));
    }

    public function responderActividad($id){
        $alumno = Auth::user();

        $actividad = Actividades::findOrFail($id);

        $tipo = $actividad->tipo;

        $preguntas = [];
        $pdf = null;
        $auditiva = null;

        if ($tipo === 'preguntas') {
            $preguntas = Preguntas::with('opciones')->where('fk_actividad', $id)->get();
        } elseif ($tipo === 'pdf') {
            $pdf = ActividadPdf::where('fk_actividad', $id)->first();
        } elseif ($tipo === 'auditiva') {
            $auditiva = ActividadAuditivaFrases::where('fk_actividad', $id)->first();
        }

        return view('alumno.responder-actividad', compact('actividad', 'tipo', 'preguntas', 'pdf', 'auditiva'));
    }

    public function guardarRespuestas(Request $request, $id){
        $usuario = Auth::user();
        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();
        $actividad = Actividades::findOrFail($id);
        $preguntas = Preguntas::with('opciones')->where('fk_actividad', $id)->get();

        foreach ($preguntas as $pregunta) {
            if ($pregunta->tipo === 'opcion_multiple') {
                $campo = 'respuestas_' . $pregunta->pk_pregunta;

                if ($request->has($campo)) {
                    $textoSeleccionado = $request->$campo;

                    $opcion = OpcionesPregunta::where('fk_pregunta', $pregunta->pk_pregunta)
                        ->where('texto_opcion', $textoSeleccionado)
                        ->first();

                    $esCorrecta = $opcion && $opcion->es_correcta ? 1 : 0;

                    DB::table('respuestas_alumno')->insert([
                        'fk_pregunta' => $pregunta->pk_pregunta,
                        'fk_alumno' => $alumno->pk_alumno,
                        'fk_actividad' => $actividad->pk_actividad,
                        'respuesta' => $textoSeleccionado,
                        'es_correcta' => $esCorrecta,
                        'calificada' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

            } else {
                $respuestas = $request->input('respuestas');

                if (!empty($respuestas[$pregunta->pk_pregunta])) {
                    DB::table('respuestas_alumno')->insert([
                        'fk_pregunta' => $pregunta->pk_pregunta,
                        'fk_alumno' => $alumno->pk_alumno,
                        'fk_actividad' => $actividad->pk_actividad,
                        'respuesta' => $respuestas[$pregunta->pk_pregunta],
                        'es_correcta' => 0,
                        'calificada' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()
            ->route('alumno.lista-actividades')
            ->with('success', 'Tus respuestas fueron enviadas correctamente.');
    }


    public function guardarRespuestaPdf(Request $request, $id){
        $request->validate([
            'archivo_alumno' => 'required|mimes:pdf|max:10240',
        ]);

        $usuario = Auth::user();
        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();
        $actividad = Actividades::findOrFail($id);

        $ruta = $request->file('archivo_alumno')->store('respuestas/pdf', 'public');

        EntregaPDFAlumno::create([
            'fk_actividad' => $actividad->pk_actividad,
            'fk_alumno' => $alumno->pk_alumno,
            'archivo_alumno' => $ruta,
            'calificacion' => null,
            'observaciones' => null,
        ]);

         return redirect()
            ->route('alumno.lista-actividades')
            ->with('success', 'Tus respuestas fueron enviadas correctamente.');
    }

    public function guardarRespuestaAuditiva(Request $request, $id){
        $request->validate([
            'archivo_respuesta' => 'required|mimes:mp3,wav,m4a|max:10240',
        ]);

        $usuario = Auth::user();
        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();
        $actividad = Actividades::findOrFail($id);

        $ruta = $request->file('archivo_respuesta')->store('respuestas/auditivas', 'public');

        RespuestaAuditivaAlumno::create([
            'fk_actividad' => $actividad->pk_actividad,
            'fk_alumno' => $alumno->pk_alumno,
            'archivo_audio_alumno' => $ruta,
            'calificacion' => null,
            'observaciones' => null,
        ]);

        return redirect()
            ->route('alumno.lista-actividades')
            ->with('success', 'Tus respuestas fueron enviadas correctamente.');
    }

    public function cargarPendientes(){
        try {
            $id = Auth::user()->pk_usuario;

            $pendAbiertas = RespuestasAlumno::where('calificada', 0)
                ->whereHas('pregunta', fn($q)=>$q->where('tipo','abierta'))
                ->whereHas('actividad', fn($q)=>$q->where('fk_docente',$id))
                ->with(['alumno.usuario','actividad.grupos.carrera','pregunta'])
                ->get()
                ->map(function($r){
                    return [
                        'tipo' => 'abierta',
                        'actividad' => $r->actividad,
                        'alumno' => $r->alumno,
                        'fk_actividad' => $r->fk_actividad,
                        'fk_alumno' => $r->fk_alumno,
                        'extra' => [
                            'pregunta' => $r->pregunta->pregunta,
                            'respuesta' => $r->respuesta
                        ]
                    ];
                });

            $pendPdf = EntregaPdfAlumno::whereNull('calificacion')
                ->whereHas('actividad', fn($q)=>$q->where('fk_docente',$id))
                ->with(['alumno.usuario','actividad.grupos.carrera'])
                ->get()
                ->map(function($r){
                    return [
                        'tipo' => 'pdf',
                        'actividad' => $r->actividad,
                        'alumno' => $r->alumno,
                        'fk_actividad' => $r->fk_actividad,
                        'fk_alumno' => $r->fk_alumno,
                        'extra' => [
                            'archivo' => $r->archivo_alumno
                        ]
                    ];
                });

            $pendAudio = RespuestaAuditivaAlumno::whereNull('calificacion')
                ->whereHas('actividad', fn($q)=>$q->where('fk_docente',$id))
                ->with(['alumno.usuario','actividad.grupos.carrera'])
                ->get()
                ->map(function($r){
                    return [
                        'tipo' => 'audio',
                        'actividad' => $r->actividad,
                        'alumno' => $r->alumno,
                        'fk_actividad' => $r->fk_actividad,
                        'fk_alumno' => $r->fk_alumno,
                        'extra' => [
                            'archivo' => $r->archivo_audio_alumno
                        ]
                    ];
                });

            $pendientes = collect()
                ->merge($pendAbiertas)
                ->merge($pendPdf)
                ->merge($pendAudio)
                ->unique(fn($i)=>$i['fk_actividad'].'-'.$i['fk_alumno'])
                ->values();

            $grupos = Grupo::whereHas('grupoMaterias', function($q) use ($id) {
                    $q->where('fk_docente', $id);
                })->with('carrera')->get();

            return view('
                docente.actividades-pendientes',
                compact(
                    'pendientes',
                    'grupos'
                )
            );

        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar las entregas: ' . $th->getMessage());
        }
    }

    public function filtrarPendientes(Request $request){
        $id = Auth::user()->pk_usuario;
        $search = $request->input('search');
        $grupoId = $request->input('grupo');

        $pendAbiertas = RespuestasAlumno::where('calificada', 0)
            ->whereHas('pregunta', fn($q) => $q->where('tipo','abierta'))
            ->whereHas('actividad', fn($q) => $q->where('fk_docente', $id))
            ->when($search, function($q, $search) {
                $q->where(function($query) use ($search) {
                    $query->whereHas('alumno.usuario', function($u) use ($search) {
                        $u->where('nombres','like',"%{$search}%")
                        ->orWhere('ap_paterno','like',"%{$search}%")
                        ->orWhere('ap_materno','like',"%{$search}%");
                    })
                    ->orWhereHas('actividad', function($a) use ($search) {
                        $a->where('nom_actividad','like',"%{$search}%");
                    });
                });
            })
            ->when($grupoId, fn($q) =>
                $q->whereHas('actividad.grupos', fn($g) => $g->where('pk_grupo', $grupoId))
            )
            ->with(['alumno.usuario','actividad.grupos.carrera','pregunta'])
            ->get()
            ->map(function($r){
                return [
                    'tipo' => 'abierta',
                    'actividad' => $r->actividad,
                    'alumno' => $r->alumno,
                    'fk_actividad' => $r->fk_actividad,
                    'fk_alumno' => $r->fk_alumno,
                    'extra' => [
                        'pregunta' => $r->pregunta->pregunta,
                        'respuesta' => $r->respuesta
                    ]
                ];
            });

        $pendPdf = EntregaPdfAlumno::whereNull('calificacion')
            ->whereHas('actividad', fn($q) => $q->where('fk_docente',$id))
            ->when($search, function($q, $search) {
                $q->where(function($query) use ($search) {
                    $query->whereHas('alumno.usuario', function($u) use ($search) {
                        $u->where('nombres','like',"%{$search}%")
                        ->orWhere('ap_paterno','like',"%{$search}%")
                        ->orWhere('ap_materno','like',"%{$search}%");
                    })
                    ->orWhereHas('actividad', function($a) use ($search) {
                        $a->where('nom_actividad','like',"%{$search}%");
                    });
                });
            })
            ->when($grupoId, fn($q) =>
                $q->whereHas('actividad.grupos', fn($g) => $g->where('pk_grupo',$grupoId))
            )
            ->with(['alumno.usuario','actividad.grupos.carrera'])
            ->get()
            ->map(function($r){
                return [
                    'tipo' => 'pdf',
                    'actividad' => $r->actividad,
                    'alumno' => $r->alumno,
                    'fk_actividad' => $r->fk_actividad,
                    'fk_alumno' => $r->fk_alumno,
                    'extra' => [
                        'archivo' => $r->archivo_alumno
                    ]
                ];
            });

        $pendAudio = RespuestaAuditivaAlumno::whereNull('calificacion')
            ->whereHas('actividad', fn($q) => $q->where('fk_docente',$id))
            ->when($search, function($q, $search) {
                $q->where(function($query) use ($search) {
                    $query->whereHas('alumno.usuario', function($u) use ($search) {
                        $u->where('nombres','like',"%{$search}%")
                        ->orWhere('ap_paterno','like',"%{$search}%")
                        ->orWhere('ap_materno','like',"%{$search}%");
                    })
                    ->orWhereHas('actividad', function($a) use ($search) {
                        $a->where('nom_actividad','like',"%{$search}%");
                    });
                });
            })
            ->when($grupoId, fn($q) =>
                $q->whereHas('actividad.grupos', fn($g) => $g->where('pk_grupo',$grupoId))
            )
            ->with(['alumno.usuario','actividad.grupos.carrera'])
            ->get()
            ->map(function($r){
                return [
                    'tipo' => 'audio',
                    'actividad' => $r->actividad,
                    'alumno' => $r->alumno,
                    'fk_actividad' => $r->fk_actividad,
                    'fk_alumno' => $r->fk_alumno,
                    'extra' => [
                        'archivo' => $r->archivo_audio_alumno
                    ]
                ];
            });

        $pendientes = collect()
            ->merge($pendAbiertas)
            ->merge($pendPdf)
            ->merge($pendAudio)
            ->unique(fn($i) => $i['fk_actividad'].'-'.$i['fk_alumno'])
            ->values();

        return view('partials.tabla_pendientes', compact('pendientes'));
    }


    public function cargarActividadPendiente($pk_actividad, $pk_alumno){
        try {
            $actividad = Actividades::with('preguntas')->findOrFail($pk_actividad);

            $alumno = Alumno::with('usuario')->findOrFail($pk_alumno);

            $respuestas = RespuestasAlumno::with([
                    'pregunta',
                    'alumno.usuario'
                ])
                ->where('fk_actividad', $pk_actividad)
                ->where('fk_alumno', $pk_alumno)
                ->get();

            return view('docente.revisar-actividad', compact(
                'actividad',
                'alumno',
                'respuestas'
            ));

        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar la actividad: ' . $th->getMessage());
        }
    }

    public function calificarRespuesta(Request $request, $id){
        $calificacion = $request->input('calificacion');

        $respuesta = RespuestasAlumno::findOrFail($id);
        $respuesta->calificada = 1;
        $respuesta->es_correcta = $calificacion;
        $respuesta->save();

        return back()->with('success', 'Respuesta calificada correctamente.');
    }

    public function cargarActividadPendientePDF($pk_actividad, $pk_alumno){
        try {
            $actividad = Actividades::findOrFail($pk_actividad);
            $entrega = EntregaPDFAlumno::where('fk_actividad', $pk_actividad)
                            ->where('fk_alumno', $pk_alumno)
                            ->firstOrFail();
            $alumno = Alumno::with('usuario')->findOrFail($pk_alumno);

            return view(
                'docente.revisar-actividad-pdf',
                compact(
                    'entrega',
                    'actividad',
                    'alumno'
                )
            );

        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar la actividad: ' . $th->getMessage());
        }
    }

    public function calificarRespuestaPDF(Request $request){
        try {
            $request->validate([
                'pk_entrega'   => 'required|integer|exists:entrega_pdf_alumno,pk_entrega',
                'calificacion' => 'required|numeric|min:0|max:10',
                'observaciones'=> 'nullable|string'
            ]);

            $entrega = EntregaPDFAlumno::findOrFail($request->pk_entrega);

            $entrega->calificacion  = $request->calificacion;
            $entrega->observaciones = $request->observaciones;
            $entrega->save();


            return response()->json([
                'mensaje' => 'Calificación guardada correctamente.',
                'ruta' => route('docente.actividades-pendientes'),
                'class' => 'success'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Ocurrió un error al guardar la calificación.',
                'detalle' => $th->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function cargarActividadPendienteAudio($pk_actividad, $pk_alumno){
        try {
            $actividad = Actividades::findOrFail($pk_actividad);
            $act_audio = ActividadAuditivaFrases::where('fk_actividad', $pk_actividad)
                            ->firstOrFail();
            $entrega = RespuestaAuditivaAlumno::where('fk_actividad', $pk_actividad)
                            ->where('fk_alumno', $pk_alumno)
                            ->firstOrFail();
            $alumno = Alumno::with('usuario')->findOrFail($pk_alumno);

            return view(
                'docente.revisar-actividad-audio',
                compact(
                    'actividad',
                    'act_audio',
                    'entrega',
                    'alumno'
                )
            );

        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar la actividad: ' . $th->getMessage());
        }
    }

    public function calificarRespuestaAudio(Request $request){
        try {
            $request->validate([
                'pk_respuesta'   => 'required|integer|exists:respuesta_auditiva_alumno,pk_respuesta',
                'calificacion' => 'required|numeric|min:0|max:10',
                'observaciones'=> 'nullable|string'
            ]);

            $entrega = RespuestaAuditivaAlumno::findOrFail($request->pk_respuesta);

            $entrega->calificacion  = $request->calificacion;
            $entrega->observaciones = $request->observaciones;
            $entrega->save();


            return response()->json([
                'mensaje' => 'Calificación guardada correctamente.',
                'ruta' => route('docente.actividades-pendientes'),
                'class' => 'success'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Ocurrió un error al guardar la calificación.',
                'detalle' => $th->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

}

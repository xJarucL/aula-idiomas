<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Tipo_usuario;
use App\Models\ActividadGrupo;
use App\Models\EntregaPDFAlumno;
use App\Models\RespuestaAuditivaAlumno;
use App\Models\GrupoAlumno;
use App\Models\GrupoMateria;
use App\Models\Grupo;
use App\Models\Calificaciones;
use App\Models\RespuestasAlumno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AlumnoController extends Controller
{
    public function listaAlumnos(Request $request){
        $query = Alumno::with(['usuario', 'grupos.grupo.carrera', 'calificaciones']);

        if ($request->filled('search')) {
            $search = str_replace(' ', '', $request->input('search'));

            $query->where(function ($query) use ($search) {
                $query->whereHas('usuario', function ($q) use ($search) {
                    $q->whereRaw("REPLACE(CONCAT(matricula, nombres, ap_paterno, ap_materno), ' ', '') LIKE ?", ["%{$search}%"])
                        ->orWhere('matricula', 'like', "%{$search}%")
                        ->orWhere('nombres', 'like', "%{$search}%")
                        ->orWhere('ap_paterno', 'like', "%{$search}%")
                        ->orWhere('ap_materno', 'like', "%{$search}%");
                })
                ->orWhereHas('grupos.grupo.carrera', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('abreviatura', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('carrera')) {
            $query->whereHas('grupos.grupo.carrera', function ($q) use ($request) {
                $q->where('pk_carrera', $request->carrera);
            });
        }

        if ($request->filled('promedio')) {
            $promedioFiltro = $request->promedio;

            $query->whereHas('calificaciones', function ($q) use ($promedioFiltro) {
                $q->selectRaw('avg(calificacion) as promedio')
                    ->groupBy('fk_alumno')
                    ->havingRaw('avg(calificacion) >= ?', [$promedioFiltro]);
            });
        }

        $alumnos = $query->paginate(10);
        $carreras = Carrera::all();

        foreach ($alumnos as $alumno) {
            $promedio = $alumno->calificaciones->avg('calificacion');
            $alumno->promedio = $promedio ? number_format($promedio, 1) : 'Sin registro';
        }

        if ($request->ajax()) {
            return view('partials.tabla_alumnos', compact('alumnos', 'carreras'))->render();
        }

        return view('coordinacion.lista-alumnos', compact('alumnos', 'carreras'));
    }


    public function listaAlumnosDeshabilitados(Request $request){
        $query = Alumno::onlyTrashed()->with(['usuario', 'grupos.grupo.carrera', 'calificaciones']);

        if ($request->filled('search')) {
            $search = str_replace(' ', '', $request->input('search'));

            $query->where(function ($query) use ($search) {
                $query->whereHas('usuario', function ($q) use ($search) {
                    $q->whereRaw("REPLACE(CONCAT(matricula, nombres, ap_paterno, ap_materno), ' ', '') LIKE ?", ["%{$search}%"])
                        ->orWhere('matricula', 'like', "%{$search}%")
                        ->orWhere('nombres', 'like', "%{$search}%")
                        ->orWhere('ap_paterno', 'like', "%{$search}%")
                        ->orWhere('ap_materno', 'like', "%{$search}%");
                })
                ->orWhereHas('grupos.grupo.carrera', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('abreviatura', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('carrera')) {
            $query->whereHas('grupos.grupo.carrera', function ($q) use ($request) {
                $q->where('pk_carrera', $request->carrera);
            });
        }

        if ($request->filled('promedio')) {
            $promedioFiltro = $request->promedio;

            $query->whereHas('calificaciones', function ($q) use ($promedioFiltro) {
                $q->selectRaw('avg(calificacion) as promedio')
                    ->groupBy('fk_alumno')
                    ->havingRaw('avg(calificacion) >= ?', [$promedioFiltro]);
            });
        }

        $alumnos = $query->paginate(10);
        $carreras = Carrera::all();

        foreach ($alumnos as $alumno) {
            $promedio = $alumno->calificaciones->avg('calificacion');
            $alumno->promedio = $promedio ? number_format($promedio, 1) : 'Sin registro';
        }

        if ($request->ajax()) {
            return view('partials.tabla_alumnos', compact('alumnos', 'carreras'))->render();
        }

        return view('coordinacion.lista-alumnos', compact('alumnos', 'carreras'));
    }


    public function store(Request $request){
        try {
            $validated = $request->validate([
                'matricula' => 'required|unique:users,matricula|max:9',
                'nombres' => 'required|string|max:100',
                'ap_paterno' => 'required|string|max:100',
                'ap_materno' => 'nullable|string|max:100',
                'fk_grupo' => 'required|exists:grupo,pk_grupo',
                'img_user' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $imgPath = null;
            if ($request->hasFile('img_user')) {
                $imgPath = $request->file('img_user')->store('img_usuarios', 'public');
            }

            DB::beginTransaction();

            $usuario = User::create([
                'matricula' => $validated['matricula'],
                'nombres' => $validated['nombres'],
                'ap_paterno' => $validated['ap_paterno'],
                'ap_materno' => $validated['ap_materno'] ?? '',
                'password' => Hash::make($validated['matricula']),
                'img_user' => $imgPath,
                'fk_tipo_usuario' => 1,
            ]);

            $alumno = Alumno::create([
                'fk_usuario' => $usuario->pk_usuario,
            ]);

            DB::table('grupo_alumno')->insert([
                'fk_alumno' => $alumno->pk_alumno,
                'fk_grupo' => $validated['fk_grupo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'mensaje' => 'Alumno registrado correctamente.',
                'ruta' => route('coordinacion.lista-alumnos'),
                'class' => 'success'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación.',
                'errores' => $e->errors(),
                'class' => 'error'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Ocurrió un error al registrar el alumno.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function eliminarAlumno($id){
        try {
            $alumno = Alumno::with('usuario')->findOrFail($id);

            if ($alumno->usuario) {
                $alumno->usuario->delete();
            }

            $alumno->delete();

            return redirect()
                ->route('coordinacion.lista-alumnos')
                ->with('success', 'Alumno deshabilitado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al deshabilitar el alumno: ' . $e->getMessage());
        }
    }

    public function restaurarAlumno($id){
        try {
            $alumno = Alumno::onlyTrashed()
                ->with(['usuario' => function ($q) {
                    $q->withTrashed();
                }])
                ->findOrFail($id);

            if ($alumno->usuario && $alumno->usuario->trashed()) {
                $alumno->usuario->restore();
            }

            $alumno->restore();

            return redirect()
                ->route('coordinacion.lista-alumnos-deshabilitados')
                ->with('success', 'Alumno restaurado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al restaurar el alumno: ' . $e->getMessage());
        }
    }

    public function perfilAlumno(){
        $usuario = Auth::user();

        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();

        $historialGrupos = GrupoAlumno::withTrashed()
            ->with(['grupo' => function ($q) {
                $q->withTrashed()
                ->with(['carrera' => function ($c) {
                    $c->withTrashed();
                }]);
            }])
            ->where('fk_alumno', $alumno->pk_alumno)
            ->orderBy('created_at', 'desc')
            ->get();

        $grupoActual = $historialGrupos->first();

        $carrera = $grupoActual && $grupoActual->grupo && $grupoActual->grupo->carrera
            ? $grupoActual->grupo->carrera->nombre
            : 'Sin carrera';

        $promedio = Calificaciones::where('fk_alumno', $alumno->pk_alumno)->avg('calificacion');

        return view('alumno.perfil', [
            'usuario' => $usuario,
            'carrera' => $carrera,
            'promedio' => $promedio ?? 'N/A',
            'historialGrupos' => $historialGrupos
        ]);
    }

    public function actualizarPerfil(Request $request){
        $usuario = Auth::user();

        try {
            $validated = $request->validate([
                'img_user' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('img_user')) {
                $archivo = $request->file('img_user');
                $nombreArchivo = Str::slug($usuario->nombres . '-' . time()) . '.' . $archivo->getClientOriginalExtension();

                if ($usuario->img_user && Storage::disk('public')->exists($usuario->img_user)) {
                    Storage::disk('public')->delete($usuario->img_user);
                }

                $path = $archivo->storeAs('perfiles', $nombreArchivo, 'public');
                $usuario->img_user = $path;
            }

            $usuario->save();
            DB::commit();

            return response()->json([
                'mensaje' => 'Foto de perfil actualizada correctamente.',
                'ruta' => route('alumno.perfil'),
                'class' => 'success'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación.',
                'errores' => $e->errors(),
                'class' => 'error'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Ocurrió un error al actualizar la foto de perfil.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function actualizarPassword(Request $request){
        try {
            $validated = $request->validate([
                'password_antigua' => 'required',
                'password_nueva' => 'required|min:6',
                'password_confirmar' => 'required|min:6',
            ]);

            if ($request->password_nueva !== $request->password_confirmar) {
                return response()->json([
                    'mensaje' => 'Las contraseñas nuevas no coinciden.',
                    'class' => 'error'
                ], 422);
            }

            $user = Auth::user();

            if (!Hash::check($request->password_antigua, $user->password)) {
                return response()->json([
                    'mensaje' => 'La contraseña actual es incorrecta.',
                    'class' => 'error'
                ], 422);
            }

            if (Hash::check($request->password_nueva, $user->password)) {
                return response()->json([
                    'mensaje' => 'La nueva contraseña no puede ser igual a la actual.',
                    'class' => 'error'
                ], 422);
            }

            $user->password = Hash::make($request->password_nueva);
            $user->save();

            return response()->json([
                'mensaje' => 'Contraseña actualizada correctamente.',
                'class' => 'success'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación. Verifique los campos.',
                'errores' => $e->errors(),
                'class' => 'error'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Ocurrió un error al actualizar la contraseña.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function detalleAlumno($id){
        $usuario = User::findOrFail($id);

        $alumno = Alumno::where('fk_usuario', $id)->firstOrFail();

        $grupos = Grupo::with('carrera')
            ->whereHas('alumnos', function($q) use ($alumno) {
                $q->where('fk_alumno', $alumno->pk_alumno);
            })
            ->get();


        $carrera = $grupos->last()?->carrera;

        $promedio = Calificaciones::where('fk_alumno', $alumno->pk_alumno)->avg('calificacion');

        return view('docente.detalle-alumno', [
            'usuario' => $usuario,
            'alumno' => $alumno,
            'carrera' => $carrera?->nombre ?? 'Sin carrera',
            'promedio' => $promedio ?? 'N/A',
            'grupos' => $grupos
        ]);
    }

    public function actividadesGrupo($alumnoId, $grupoId){
         try {
            $actividadesGrupo = ActividadGrupo::with('actividad')
                ->where('fk_grupo', $grupoId)
                ->get();

            $resultado = [];
            $suma = 0;
            $contador = 0;

            foreach ($actividadesGrupo as $ag) {
                $actividad = $ag->actividad;
                $fechaFin = $ag->fecha_fin;
                $hoy = now();

                $item = [
                    'actividad' => $actividad,
                    'entrega' => null,
                    'calificacion' => null,
                    'caducada' => $hoy->gt($fechaFin),
                ];

                if ($actividad->tipo === 'preguntas') {

                    $respuestas = RespuestasAlumno::where('fk_actividad', $actividad->pk_actividad)
                        ->where('fk_alumno', $alumnoId)
                        ->get();

                    $item['entrega'] = $respuestas;

                    if ($respuestas->count() > 0) {
                        $soloCalificadas = $respuestas->filter(fn($r) => $r->calificada);

                        if ($soloCalificadas->count() > 0) {
                            $cal = round($soloCalificadas->avg('es_correcta') * 10, 2);
                            $item['calificacion'] = $cal;

                            $suma += $cal;
                            $contador++;
                        }
                    } else {
                        if ($item['caducada']) {
                            $item['calificacion'] = 0;
                            $suma += 0;
                            $contador++;
                        }
                    }
                }

                if ($actividad->tipo === 'pdf') {

                    $entregaPdf = EntregaPdfAlumno::where('fk_actividad', $actividad->pk_actividad)
                        ->where('fk_alumno', $alumnoId)
                        ->first();

                    $item['entrega'] = $entregaPdf;

                    if ($entregaPdf) {
                        $item['calificacion'] = $entregaPdf->calificacion;

                        $suma += $entregaPdf->calificacion;
                        $contador++;
                    } else {
                        if ($item['caducada']) {
                            $item['calificacion'] = 0;
                            $suma += 0;
                            $contador++;
                        }
                    }
                }

                if ($actividad->tipo === 'auditiva') {

                    $entregaAudio = RespuestaAuditivaAlumno::where('fk_actividad', $actividad->pk_actividad)
                        ->where('fk_alumno', $alumnoId)
                        ->first();

                    $item['entrega'] = $entregaAudio;

                    if ($entregaAudio) {
                        $item['calificacion'] = $entregaAudio->calificacion;

                        $suma += $entregaAudio->calificacion;
                        $contador++;
                    } else {
                        if ($item['caducada']) {
                            $item['calificacion'] = 0;
                            $suma += 0;
                            $contador++;
                        }
                    }
                }

                $resultado[] = $item;
            }

            $promedioGeneral = $contador > 0 ? round($suma / $contador, 2) : null;

            return view('docente.actividades-alumno', [
                'actividades' => $resultado,
                'alumnoId' => $alumnoId,
                'grupoId' => $grupoId,
                'promedioGeneral' => $promedioGeneral
            ]);

        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar las actividades: ' . $th->getMessage());
        }
    }

    public function cargarAlumno($id){
        $usuario = User::findOrFail($id);

        $alumno = Alumno::where('fk_usuario', $id)->firstOrFail();

        $grupos = Grupo::with('carrera')
            ->whereHas('alumnos', function($q) use ($alumno) {
                $q->where('fk_alumno', $alumno->pk_alumno);
            })
            ->get();


        $carrera = $grupos->last()?->carrera;

        $promedio = Calificaciones::where('fk_alumno', $alumno->pk_alumno)->avg('calificacion');

        return view('coordinacion.detalle-alumno', [
            'usuario' => $usuario,
            'alumno' => $alumno,
            'carrera' => $carrera?->nombre ?? 'Sin carrera',
            'promedio' => $promedio ?? 'N/A',
            'grupos' => $grupos
        ]);
    }

    public function loadAlumno($id){
        $usuario = User::findOrFail($id);

        return view('coordinacion.editar-alumno', compact('usuario'));
    }

    public function editarAlumno(Request $request){
         try {
            $validated = $request->validate([
                'nombres' => 'required|string|max:100',
                'ap_paterno' => 'required|string|max:100',
                'ap_materno' => 'nullable|string|max:100',
            ],[
                'nombres.required' => 'El nombre es obligatorio.',
                'ap_paterno.required' => 'El apellido paterno es obligatorio',
            ]);

            $usuario = User::findOrFail($request->pk_usuario);

            DB::beginTransaction();

            $usuario->nombres = $validated['nombres'];
            $usuario->ap_paterno = $validated['ap_paterno'];
            $usuario->ap_materno = $validated['ap_materno'];
            $usuario->save();

            DB::commit();

            return response()->json([
                'mensaje' => 'Alumno actualizado correctamente.',
                'ruta' => route('coordinacion.lista-alumnos'),
                'class' => 'success'
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Ocurrió un error al actualizar al alumno.',
                'detalle' => $th->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function cargarPanel(){
        $usuario = Auth::user();

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

        $promedio = round(Calificaciones::where('fk_alumno', $alumno->pk_alumno)->avg('calificacion') ?? 0, 1);

        $grupoActivo = GrupoAlumno::where('fk_alumno', $alumno->pk_alumno)->first();

        if (!$grupoActivo) {
            return view('alumno.inicio', [
                'pendientes' => collect(),
                'entregadas' => collect(),
                'noEntregadas' => collect(),
                'count_pendientes' => 0,
                'count_entregadas' => 0,
                'count_no_entregadas' => 0,
                'promedio' => $promedio,
                'porcentaje_general' => 0,
                'porcentaje_entregadas' => 0,
                'porcentaje_pendientes' => 0,
                'porcentaje_no_entregadas' => 0,
                'materia' => null,
            ]);
        }

        $grupo = Grupo::with('grupoMaterias.materia')
            ->where('pk_grupo', $grupoActivo->fk_grupo)
            ->first();

        $materia = optional($grupo->grupoMaterias->first()->materia)->nombre ?? 'Sin materia asignada';

        $ahora = now();

        $pendientes = [];
        $entregadas = [];
        $noEntregadas = [];

        $actividades = DB::table('actividad_grupo')
            ->join('actividades', 'actividad_grupo.fk_actividad', '=', 'actividades.pk_actividad')
            ->where('actividad_grupo.fk_grupo', $grupoActivo->fk_grupo)
            ->select(
                'actividades.pk_actividad',
                'actividades.nom_actividad',
                'actividades.descripcion',
                'actividades.tipo',
                'actividad_grupo.pk_asignacion',
                'actividad_grupo.fecha_inicio',
                'actividad_grupo.fecha_fin',
                'actividad_grupo.fk_grupo'
            )
            ->get();

        foreach ($actividades as $act) {
            $entregada = false;

            switch ($act->tipo) {
                case 'preguntas':
                    $entregada = DB::table('respuestas_alumno')
                        ->where('fk_actividad', $act->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->exists();
                    break;

                case 'pdf':
                    $entregada = DB::table('entrega_pdf_alumno')
                        ->where('fk_actividad', $act->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->exists();
                    break;

                case 'auditiva':
                    $entregada = DB::table('respuesta_auditiva_alumno')
                        ->where('fk_actividad', $act->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->exists();
                    break;
            }

            $fechaFin = Carbon::parse($act->fecha_fin);

            $actividadData = [
                'pk_actividad' => $act->pk_actividad,
                'nom_actividad' => $act->nom_actividad,
                'descripcion' => $act->descripcion,
                'tipo' => $act->tipo,
                'fecha_inicio' => $act->fecha_inicio,
                'fecha_fin' => $act->fecha_fin,
                'fk_grupo' => $act->fk_grupo,
                'pk_asignacion' => $act->pk_asignacion,
            ];

            if ($entregada) {
                $entregadas[] = $actividadData;
            } elseif ($ahora->lessThan($fechaFin)) {
                $pendientes[] = $actividadData;
            } else {
                $noEntregadas[] = $actividadData;
            }
        }

        $pendientes = collect($pendientes);
        $entregadas = collect($entregadas);
        $noEntregadas = collect($noEntregadas);

        $count_pendientes = $pendientes->count();
        $count_entregadas = $entregadas->count();
        $count_no_entregadas = $noEntregadas->count();

        $total_actividades = $count_pendientes + $count_entregadas + $count_no_entregadas;

        if ($total_actividades > 0) {
            $porcentaje_general = round(($count_entregadas / $total_actividades) * 100, 1);
            $porcentaje_entregadas = round(($count_entregadas / $total_actividades) * 100, 1);
            $porcentaje_pendientes = round(($count_pendientes / $total_actividades) * 100, 1);
            $porcentaje_no_entregadas = round(($count_no_entregadas / $total_actividades) * 100, 1);
        } else {
            $porcentaje_general = $porcentaje_entregadas = $porcentaje_pendientes = $porcentaje_no_entregadas = 0;
        }

        return view('alumno.inicio', compact(
            'pendientes',
            'entregadas',
            'noEntregadas',
            'count_pendientes',
            'count_entregadas',
            'count_no_entregadas',
            'promedio',
            'porcentaje_general',
            'porcentaje_entregadas',
            'porcentaje_pendientes',
            'porcentaje_no_entregadas',
            'materia'
        ));
    }

    public function misActividades(Request $request){
        $usuario = Auth::user();

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
                    'actividades.tipo',
                    'actividad_grupo.fecha_inicio',
                    'actividad_grupo.fecha_fin',
                    'actividad_grupo.fk_grupo'
                )
                ->get();

            foreach ($actividades as $act) {
                $fechaFin = Carbon::parse($act->fecha_fin);
                $entregada = false;

                if ($act->tipo === 'preguntas') {
                    $entregada = DB::table('respuestas_alumno')
                        ->where('fk_actividad', $act->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->exists();
                }

                if ($act->tipo === 'pdf') {
                    $entregada = DB::table('entrega_pdf_alumno')
                        ->where('fk_actividad', $act->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->exists();
                }

                if ($act->tipo === 'auditiva') {
                    $entregada = DB::table('respuesta_auditiva_alumno')
                        ->where('fk_actividad', $act->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->exists();
                }

                if ($entregada) {
                    $entregadas[] = $act;
                } elseif ($ahora->lessThan($fechaFin)) {
                    $pendientes[] = $act;
                } else {
                    $noEntregadas[] = $act;
                }
            }
        }

        $pendientes = collect($pendientes);
        $entregadas = collect($entregadas);
        $noEntregadas = collect($noEntregadas);

        $filtro = $request->query('filtro', 'pendientes');

        $actividadesFiltradas = match ($filtro) {
            'entregadas' => $entregadas,
            'no_entregadas' => $noEntregadas,
            default => $pendientes,
        };

        return view('alumno.lista-actividades', compact(
            'pendientes',
            'entregadas',
            'noEntregadas',
            'actividadesFiltradas',
            'filtro'
        ));
    }


}

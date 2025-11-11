<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\GrupoMateria;
use App\Models\Cuatrimestre;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\User;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    public function listaGrupos(Request $request){
        $query = Grupo::with(['carrera', 'cuatrimestre']);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                ->orWhereHas('carrera', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%$search%")
                        ->orWhere('abreviatura', 'like', "%$search%");
                })
                ->orWhereHas('cuatrimestre', function ($q2) use ($search) {
                    $q2->where('num_cuatri', 'like', "%$search%");
                })
                ->orWhere('fk_cuatrimestre', 'like', "%$search%")
                ->orWhere('año', 'like', "%$search%");
            });
        }

        if ($request->filled('cuatrimestre')) {
            $query->where('fk_cuatrimestre', $request->input('cuatrimestre'));
        }

        if ($request->filled('carrera')) {
            $query->where('fk_carrera', $request->input('carrera'));
        }

        $grupos = $query->paginate(10);
        $cuatrimestres = Cuatrimestre::all();
        $carreras = Carrera::all();

        if ($request->ajax()) {
            return view('partials.tabla_grupos', compact('grupos', 'cuatrimestres', 'carreras'))->render();
        }

        return view('coordinacion.lista-grupos', compact('grupos', 'cuatrimestres', 'carreras'));
    }

    public function listaGruposDeshabilitados(Request $request){
        $query = Grupo::onlyTrashed()->with(['carrera', 'cuatrimestre']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                ->orWhereHas('carrera', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%$search%");
                })
                ->orWhereHas('cuatrimestre', function ($q2) use ($search) {
                    $q2->where('num_cuatri', 'like', "%$search%");
                })
                ->orWhere('año', 'like', "%$search%");
            });
        }

        if ($request->filled('cuatrimestre')) {
            $query->where('fk_cuatrimestre', $request->input('cuatrimestre'));
        }

        if ($request->filled('carrera')) {
            $query->where('fk_carrera', $request->input('carrera'));
        }

        $grupos = $query->paginate(10);
        $cuatrimestres = Cuatrimestre::all();
        $carreras = Carrera::all();

        if ($request->ajax()) {
            return view('partials.tabla_grupos', compact('grupos', 'cuatrimestres', 'carreras'))->render();
        }

        return view('coordinacion.lista-grupos', compact('grupos', 'cuatrimestres', 'carreras'));
    }

    public function guardarGrupo(Request $request){
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'año' => 'required|digits:4',
                'fk_carrera' => 'required|integer|exists:carrera,pk_carrera',
                'fk_cuatrimestre' => 'required|integer|exists:cuatrimestre,pk_cuatrimestre',
                'fk_materia' => 'required|integer|exists:materia,pk_materia',
                'fk_docente' => 'required|integer|exists:users,pk_usuario',
            ], [
                'nombre.required' => 'El nombre del grupo es obligatorio.',
                'año.required' => 'El año es obligatorio.',
                'año.digits' => 'El año debe contener exactamente 4 números.',
                'fk_carrera.required' => 'Debe seleccionar una carrera.',
                'fk_carrera.exists' => 'La carrera seleccionada no existe.',
                'fk_cuatrimestre.required' => 'Debe seleccionar un cuatrimestre.',
                'fk_cuatrimestre.exists' => 'El cuatrimestre seleccionado no existe.',
                'fk_materia.required' => 'Debe seleccionar una materia.',
                'fk_materia.exists' => 'La materia seleccionada no existe.',
                'fk_docente.required' => 'Debe seleccionar un docente.',
                'fk_docente.exists' => 'El docente seleccionado no existe.',
            ]);

            DB::beginTransaction();

            $grupoId = DB::table('grupo')->insertGetId([
                'nombre' => $validated['nombre'],
                'año' => $validated['año'],
                'fk_carrera' => $validated['fk_carrera'],
                'fk_cuatrimestre' => $validated['fk_cuatrimestre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('grupo_materia')->insert([
                'fk_materia' => $validated['fk_materia'],
                'fk_grupo' => $grupoId,
                'fk_docente' => $validated['fk_docente'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grupo creado correctamente.',
                'ruta' => route('coordinacion.lista-grupos'),
                'class' => 'success'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors(),
                'class' => 'error'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el grupo: ' . $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function eliminarGrupo($id){
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        return redirect()->route('coordinacion.lista-grupos')->with('success', 'Grupo eliminado correctamente.');
    }

    public function restaurarGrupo($id){
        $grupo = Grupo::withTrashed()->findOrFail($id);
        $grupo->restore();

        return redirect()->route('coordinacion.lista-grupos-deshabilitados')->with('success', 'Grupo restaurado correctamente.');
    }

    public function listaGruposDocente(Request $request){
        try {
            $usuario = Auth::user();

            $query = GrupoMateria::with(['grupo.carrera', 'materia'])
                ->with(['grupo' => function ($q) {
                    $q->withCount('alumnos');
                }])
                ->where('fk_docente', $usuario->pk_usuario);

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->whereHas('grupo', function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                        ->orWhereHas('carrera', function ($sub) use ($search) {
                            $sub->where('nombre', 'LIKE', "%{$search}%")
                                ->orWhere('abreviatura', 'LIKE', "%{$search}%");
                        });
                });
            }

            if ($request->filled('fk_carrera') && $request->fk_carrera != 0) {
                $query->whereHas('grupo', function ($q) use ($request) {
                    $q->where('fk_carrera', $request->fk_carrera);
                });
            }

            if ($request->filled('fk_cuatrimestre') && $request->fk_cuatrimestre != 0) {
                $query->whereHas('grupo', function ($q) use ($request) {
                    $q->where('fk_cuatrimestre', $request->fk_cuatrimestre);
                });
            }

            if ($request->filled('año') && $request->año != 0) {
                $query->whereHas('grupo', function ($q) use ($request) {
                    $q->where('año', $request->año);
                });
            }

            $grupos = $query->get();
            $carreras = \App\Models\Carrera::orderBy('nombre')->get();

            if ($request->ajax()) {
                return view('partials.tabla_mi_grupo', compact('grupos'))->render();
            }

            return view('docente.mis-grupos', compact('grupos', 'carreras'));

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function detalleGrupo($id){
        $grupo = Grupo::with([
            'carrera',
            'cuatrimestre',
            'alumnos.usuario',
            'actividades'
        ])->find($id);

        if (!$grupo) {
            abort(404, 'Grupo no encontrado');
        }

        $grupo->actividades = $grupo->actividades->map(function ($actividad) {
            return [
                'pk_actividad' => $actividad->pk_actividad,
                'nom_actividad' => $actividad->nom_actividad,
                'descripcion' => $actividad->descripcion,
                'tipo' => $actividad->tipo,
                'fecha_inicio' => $actividad->pivot->fecha_inicio,
                'fecha_fin' => $actividad->pivot->fecha_fin,
            ];
        });

        return view('docente.detalle-grupo', compact('grupo'));
    }

    public function cargarRegistroGrupo(){
        $carreras = Carrera::all();
        $cuatrimestres = Cuatrimestre::all();
        $materias = Materia::all();
        $docentes = User::where('fk_tipo_usuario', 2)->get();
        return view('coordinacion.registro-grupo', compact('carreras', 'cuatrimestres', 'materias', 'docentes'));
    }

    public function cargarAlumnos($id){
       $grupo_origen = Grupo::with('carrera', 'cuatrimestre')->findOrFail($id);
        $grupos_destino = Grupo::with('carrera')->where('pk_grupo', '!=', $id)->get();

        return view('coordinacion.asignar-grupo', compact('grupo_origen', 'grupos_destino'));
    }

    public function asignarGrupo(Request $request){
        try {
            $validated = $request->validate([
                'grupo_origen' => 'required|integer|exists:grupo,pk_grupo',
                'grupo_destino' => 'required|integer|exists:grupo,pk_grupo',
            ]);

            DB::beginTransaction();

            $alumnos = DB::table('grupo_alumno')
                ->where('fk_grupo', $validated['grupo_origen'])
                ->whereNull('deleted_at')
                ->pluck('fk_alumno');

            foreach ($alumnos as $alumno) {
                DB::table('grupo_alumno')
                    ->where('fk_alumno', $alumno)
                    ->where('fk_grupo', $validated['grupo_origen'])
                    ->update(['deleted_at' => now(), 'updated_at' => now()]);

                DB::table('grupo_alumno')->insert([
                    'fk_alumno' => $alumno,
                    'fk_grupo' => $validated['grupo_destino'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('grupo')
                ->where('pk_grupo', $validated['grupo_origen'])
                ->update(['deleted_at' => now(), 'updated_at' => now()]);

            DB::commit();

            return redirect()
                ->route('coordinacion.lista-grupos')
                ->with('success', 'Los alumnos fueron reasignados correctamente y el grupo anterior fue desactivado.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Ocurrió un error al reasignar el grupo: ' . $e->getMessage());
        }
    }


}

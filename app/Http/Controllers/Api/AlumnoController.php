<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Tipo_usuario;
use App\Models\GrupoAlumno;
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


class AlumnoController extends Controller
{
    public function guardarAlumno(Request $request){
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
                'success' => true,
                'message' => 'Alumno guardado correctamente.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ]);
        }
    }

    public function listaAlumnos(Request $request){
        $query = Alumno::with(['usuario', 'grupos.grupo.carrera', 'calificaciones'])->withTrashed()->orderBy('created_at', 'desc');

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

        $alumnos->getCollection()->transform(function ($alumno) {
            $promedio = $alumno->calificaciones->avg('calificacion');
            $alumno->promedio = $promedio ? round($promedio, 1) : null;
            return $alumno;
        });

        return response()->json([
            'success' => true,
            'data' => $alumnos->items(),
            'pagination' => [
                'current_page' => $alumnos->currentPage(),
                'last_page' => $alumnos->lastPage(),
                'per_page' => $alumnos->perPage(),
                'total' => $alumnos->total(),
            ],
        ]);
    }

    public function eliminarAlumno($id){
        $alumno = User::findOrFail($id);
        $alumno->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alumno deshabilitado correctamente',
            'data' => $alumno,
        ]);
    }

    public function restaurarAlumno($id){
        $alumno = User::withTrashed()->findOrFail($id);
        $alumno->restore();

        return response()->json([
            'success' => true,
            'message' => 'Alumno restaurado correctamente',
            'data' => $alumno,
        ]);
    }

    public function perfilAlumno($id){

        $usuario = User::where('fk_tipo_usuario', 1)->find($id);

        $alumno = Alumno::where('fk_usuario', $usuario->pk_usuario)->first();

        $grupoAlumno = GrupoAlumno::where('fk_alumno', $alumno->pk_alumno)->first();

        $carrera = null;
        if($grupoAlumno) {
            $grupo = Grupo::find($grupoAlumno->fk_grupo);
            if($grupo) {
                $carrera = $grupo->carrera;
            }
        }

        $promedio = Calificaciones::where('fk_alumno', $alumno->pk_alumno)->avg('calificacion');

        return response()->json([
            'success' => true,
            'message' => 'Alumno obtenido correctamente',
            'usuario' => $usuario,
            'carrera' => $carrera ? $carrera->nombre : 'Sin carrera',
            'promedio' => $promedio ?? 'N/A',
        ]);
    }

    public function detalleAlumno($id){
        $alumno = Alumno::with([
            'usuario',
            'grupos.grupo.carrera',
            'grupos.grupo.cuatrimestre',
        ])->find($id);

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        $grupo = $alumno->grupos->first()?->grupo;

        $actividadesGrupo = $grupo ? $grupo->actividades : collect();

        $respuestas = RespuestasAlumno::where('fk_alumno', $alumno->pk_alumno)->get();

        $actividades = $actividadesGrupo->map(function ($act) use ($respuestas) {
            $entregado = $respuestas->where('fk_actividad', $act->pk_actividad)->isNotEmpty();

            return [
                'pk_actividad' => $act->pk_actividad,
                'nom_actividad' => $act->nom_actividad,
                'tipo' => $act->tipo,
                'entregado' => $entregado,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'alumno' => [
                    'pk_alumno' => $alumno->pk_alumno,
                    'nombre_completo' => trim("{$alumno->usuario->nombres} {$alumno->usuario->ap_paterno} {$alumno->usuario->ap_materno}"),
                    'nombres' => $alumno->usuario->nombres,
                    'ap_paterno' => $alumno->usuario->ap_paterno,
                    'ap_materno' => $alumno->usuario->ap_materno,
                    'matricula' => $alumno->usuario->matricula,
                    'email' => $alumno->usuario->email,
                ],
                'grupo' => [
                    'pk_grupo' => $grupo->pk_grupo ?? null,
                    'nombre' => $grupo->nombre ?? null,
                    'año' => $grupo->año ?? null,
                    'cuatrimestre' => $grupo->cuatrimestre ?? null,
                    'carrera' => $grupo->carrera ?? null,
                ],
                'actividades' => $actividades,
            ]
        ]);
    }

    function obtenerAlumnos(){
        try {
            $alumnos = Alumno::with('usuario', 'grupos.grupo.carrera')->get();

            return response()->json([
                'success' => true,
                'data' => $alumnos
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal al obtener los alumnos',
                'error' => $th
            ]);
        }
    }

    public function obtenerAlumnosPorGrupo(){
        $grupos = Grupo::with(['alumnos.usuario', 'carrera', 'cuatrimestre'])->get();

        $respuesta = [];

        foreach ($grupos as $grupo) {
            $lista = [];

            foreach ($grupo->alumnos as $a) {
                $lista[] = [
                    'pk_usuario' => $a->usuario->pk_usuario,
                    'nombres' => $a->usuario->nombres,
                    'ap_paterno' => $a->usuario->ap_paterno,
                    'ap_materno' => $a->usuario->ap_materno,
                ];
            }

            $nombreGrupo =
                        $grupo->fk_cuatrimestre. ' ' .
                        $grupo->nombre . ' ' .
                        $grupo->carrera->abreviatura . ' ' .
                        $grupo->año;

            $respuesta[$nombreGrupo] = $lista;
        }

        return response()->json([
            'success' => true,
            'data' => $respuesta
        ]);
    }


}


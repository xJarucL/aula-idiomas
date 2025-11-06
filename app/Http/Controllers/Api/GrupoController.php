<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\GrupoMateria;
use App\Models\GrupoAlumno;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\User;
use App\Models\Alumno;

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

    public function formGrupo(){
        $carreras = Carrera::All();
        $materias = Materia::All();
        $docentes = User::where('fk_tipo_usuario', 2)->get();

        return response()->json([
            'success' => true,
            'message' => 'Data cargada correctamente',
            'carreras' => $carreras,
            'materias' => $materias,
            'docentes' => $docentes,
        ]);
    }

    public function guardarGrupo(Request $request){
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'año' => 'required|string|max:10',
                'fk_carrera' => 'required|integer|exists:carrera,pk_carrera',
                'fk_cuatrimestre' => 'required|integer|exists:cuatrimestre,pk_cuatrimestre',
                'fk_materia' => 'required|integer|exists:materia,pk_materia',
                'fk_docente' => 'required|integer|exists:users,pk_usuario',
            ]);

            $grupo = DB::table('grupo')->insertGetId([
                'nombre' => $validated['nombre'],
                'año' => $validated['año'],
                'fk_carrera' => $validated['fk_carrera'],
                'fk_cuatrimestre' => $validated['fk_cuatrimestre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('grupo_materia')->insert([
                'fk_materia' => $validated['fk_materia'],
                'fk_grupo' => $grupo,
                'fk_docente' => $validated['fk_docente'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grupo creado correctamente.',
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el grupo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function asignarGrupoAlumno(Request $request){
        try {
            $validated = $request->validate([
                'fk_grupo' => 'required|exists:grupo,pk_grupo',
                'alumnos' => 'required|array',
                'alumnos.*' => 'exists:users,pk_usuario',
            ], [
                'fk_grupo.required' => 'El identificador del grupo es obligatorio.',
                'alumnos.required' => 'Seleccionar mínimo 1 alumno.',
                'alumnos.array' => 'Los alumnos no se recibieron en el formato esperado.',
            ]);

            foreach ($request->alumnos as $alumnoId) {
                $alumno = Alumno::where('fk_usuario', $alumnoId)->first();

                if (!$alumno) continue;

                $grupoExistente = GrupoAlumno::where('fk_alumno', $alumno->pk_alumno)->first();

                if ($grupoExistente) {
                    if ($grupoExistente->fk_grupo == $request->fk_grupo) {
                        continue;
                    }
                    $grupoExistente->delete();
                }
                GrupoAlumno::create([
                    'fk_grupo' => $request->fk_grupo,
                    'fk_alumno' => $alumno->pk_alumno,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Alumnos asignados correctamente al grupo.',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal durante la asignación del grupo.',
                'error' => $th->getMessage(),
            ]);
        }
    }

    function gruposActualesAlumno($id){
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

        $grupos = GrupoAlumno::with(['grupo.carrera'])
            ->withTrashed()
            ->where('fk_alumno', $alumno->pk_alumno)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Grupos obtenidos correctamente',
            'usuario' => $usuario,
            'alumno' => $alumno,
            'grupos' => $grupos,
        ]);
    }

}

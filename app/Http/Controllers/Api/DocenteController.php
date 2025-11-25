<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\GrupoMateria;
use App\Models\Actividades;
use App\Models\RespuestasAlumno;
use App\Models\EntregaPDFAlumno;
use App\Models\RespuestaAuditivaAlumno;
use App\Models\Mensajes;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocenteController extends Controller
{

    public function panel($id){
        try {
            $usuario = User::findOrFail($id);
            $id = $usuario->pk_usuario;

            $grupos = GrupoMateria::with(['grupo.carrera', 'materia'])
                ->with(['grupo' => function ($q) {
                    $q->withCount('alumnos');
                }])
                ->where('fk_docente', $id)
                ->whereNull('deleted_at')
                ->whereHas('grupo', function ($q) {
                    $q->whereNull('deleted_at');
                });


            $actividades = Actividades::where('fk_docente', $id)->get();

            $actividadesAbiertas = RespuestasAlumno::where('calificada', 0)
                ->whereHas('pregunta', fn($q) => $q->where('tipo', 'abierta'))
                ->whereIn('fk_actividad', function ($q) use ($id) {
                    $q->select('pk_actividad')->from('actividades')->where('fk_docente', $id);
                })
                ->select('fk_actividad', 'fk_alumno')
                ->groupBy('fk_actividad', 'fk_alumno')
                ->get();

            $actividadesPDF = EntregaPDFAlumno::whereNull('calificacion')
                ->whereIn('fk_actividad', function ($q) use ($id) {
                    $q->select('pk_actividad')->from('actividades')->where('fk_docente', $id);
                })
                ->select('fk_actividad', 'fk_alumno')
                ->groupBy('fk_actividad', 'fk_alumno')
                ->get();

            $actividadesAudio = RespuestaAuditivaAlumno::whereNull('calificacion')
                ->whereIn('fk_actividad', function ($q) use ($id) {
                    $q->select('pk_actividad')->from('actividades')->where('fk_docente', $id);
                })
                ->select('fk_actividad', 'fk_alumno')
                ->groupBy('fk_actividad', 'fk_alumno')
                ->get();

            $actividadesRevisionCount = collect()
                ->merge($actividadesAbiertas)
                ->merge($actividadesPDF)
                ->merge($actividadesAudio)
                ->unique(fn($item) => $item->fk_actividad . '-' . $item->fk_alumno)
                ->count();

            $alumnosCount = GrupoMateria::where('fk_docente', $id)
                ->whereNull('deleted_at')
                ->whereHas('grupo', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->with([
                    'grupo' => function ($q) {
                        $q->whereNull('deleted_at');
                    },
                    'grupo.alumnos.usuario' => function ($q) {
                        $q->where('fk_tipo_usuario', 1);
                    }
                ])
                ->get()
                ->flatMap(function ($gm) {
                    return $gm->grupo->alumnos
                        ->map(fn($alumno) => $alumno->usuario)
                        ->filter();
                })
                ->unique('pk_usuario')
                ->count();


            $gruposCount = $grupos->count();
            $actividadesCount = $actividades->count();

            $ultimoMensaje = Mensajes::with('deUsuario')
                                    ->where('para_usuario', $id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();

            return response()->json([
                'success' => true,
                'message' => 'Datos Obtenidos correctamente',
                'usuario' => $usuario ?? null,
                'gruposCount' => $gruposCount ?? 0,
                'actividadesCount' => $actividadesCount ?? 0,
                'alumnosCount' => $alumnosCount ?? 0,
                'actividadesRevisionCount' => $actividadesRevisionCount ?? 0,
                'ultimoMensaje' => $ultimoMensaje
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function guardarDocente(Request $request){
        try {
            $validated = $request->validate([
                'email' => 'required|unique:users',
                'nombres' => 'required|string|max:100',
                'ap_paterno' => 'required|string|max:100',
                'ap_materno' => 'nullable|string|max:100',
                'img_user' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],[
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.unique' => 'El correo electrónico ya está registrado.',

                'nombres.required' => 'El nombre es obligatorio.',
                'nombres.string' => 'El nombre debe ser texto.',
                'nombres.max' => 'El nombre no puede exceder los 100 caracteres.',

                'ap_paterno.required' => 'El apellido paterno es obligatorio.',
                'ap_paterno.string' => 'El apellido paterno debe ser texto.',
                'ap_paterno.max' => 'El apellido paterno no puede exceder los 100 caracteres.',

                'ap_materno.string' => 'El apellido materno debe ser texto.',
                'ap_materno.max' => 'El apellido materno no puede exceder los 100 caracteres.',

                'img_user.image' => 'El archivo debe ser una imagen.',
                'img_user.mimes' => 'La imagen debe estar en formato JPG, JPEG o PNG.',
                'img_user.max' => 'La imagen no debe pesar más de 2 MB.',
            ]);

            $imgPath = null;
            if ($request->hasFile('img_user')) {
                $imgPath = $request->file('img_user')->store('img_usuarios', 'public');
            }

            DB::beginTransaction();

            $usuario = User::create([
                'email' => $validated['email'],
                'nombres' => $validated['nombres'],
                'ap_paterno' => $validated['ap_paterno'],
                'ap_materno' => $validated['ap_materno'] ?? '',
                'password' => Hash::make($validated['email']),
                'img_user' => $imgPath,
                'fk_tipo_usuario' => 2,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Docente guardado correctamente.'
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

    public function listaDocentes(Request $request){
        $query = User::where('fk_tipo_usuario', 2)->withTrashed() ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(nombres, ' ', ap_paterno, ' ', IFNULL(ap_materno, '')) LIKE ?", ["%{$search}%"])
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $docentes = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Lista de docentes obtenida correctamente.',
            'data' => $docentes->items(),
            'pagination' => [
                'total' => $docentes->total(),
                'current_page' => $docentes->currentPage(),
                'last_page' => $docentes->lastPage(),
                'per_page' => $docentes->perPage(),
            ],
        ]);
    }

    public function show($id){
        $docente = User::where('fk_tipo_usuario', 2)->find($id);

        if (!$docente) {
            return response()->json([
                'success' => false,
                'message' => 'Docente no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Docente obtenido correctamente',
            'data' => $docente,
        ]);
    }

    public function update(Request $request, $id){
        $docente = User::where('fk_tipo_usuario', 2)->find($id);

        if (!$docente) {
            return response()->json([
                'success' => false,
                'message' => 'Docente no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id. ',pk_usuario',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $docente->nombres = $request->input('nombres');
        $docente->ap_paterno = $request->input('ap_paterno');
        $docente->ap_materno = $request->input('ap_materno');
        $docente->email = $request->input('email');
        $docente->save();

        return response()->json([
            'success' => true,
            'message' => 'Docente actualizado correctamente',
            'data' => $docente,
        ]);
    }

    public function eliminarDocente($id){
        $docente = User::findOrFail($id);
        $docente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Docente deshabilitado correctamente',
            'data' => $docente,
        ]);
    }

    public function restaurarDocente($id){
        $docente = User::withTrashed()->findOrFail($id);
        $docente->restore();

        return response()->json([
            'success' => true,
            'message' => 'Docente restaurado correctamente',
            'data' => $docente,
        ]);
    }
}


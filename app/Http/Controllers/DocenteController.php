<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Calificaciones;
use App\Models\Grupo;
use App\Models\GrupoMateria;
use App\Models\GrupoAlumno;
use App\Models\Actividades;
use App\Models\RespuestasAlumno;
use App\Models\EntregaPDFAlumno;
use App\Models\RespuestaAuditivaAlumno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocenteController extends Controller
{
    public function listaDocentes(Request $request){
        $query = User::where('fk_tipo_usuario', 2);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(nombres, ' ', ap_paterno, ' ', IFNULL(ap_materno, '')) LIKE ?", ["%{$search}%"])
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $docentes = $query->paginate(10);

        if ($request->ajax()) {
            return view('partials.tabla_docentes', compact('docentes'))->render();
        }

        return view('coordinacion.lista-docentes', compact('docentes'));
    }


    public function listaDocentesDeshabilitados(Request $request){
        $query = User::onlyTrashed()->where('fk_tipo_usuario', 2);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(nombres, ' ', ap_paterno, ' ', IFNULL(ap_materno, '')) LIKE ?", ["%{$search}%"])
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $docentes = $query->paginate(10);

        if ($request->ajax()) {
            return view('partials.tabla_docentes', compact('docentes'))->render();
        }

        return view('coordinacion.lista-docentes', compact('docentes'));
    }

    public function eliminarDocente($id){
        $docente = User::findOrFail($id);
        $docente->delete();

        return redirect()->route('coordinacion.lista-docentes')->with('success', 'Docente deshabilitado correctamente.');
    }

    public function restaurarDocente($id){
        $docente = User::withTrashed()->findOrFail($id);
        $docente->restore();

        return redirect()->route('coordinacion.lista-docentes-deshabilitados')->with('success', 'Docente restaurado correctamente.');
    }

     public function store(Request $request){
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
                'mensaje' => 'Docente registrado correctamente.',
                'ruta' => route('coordinacion.lista-docentes'),
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
                'mensaje' => 'Ocurrió un error al registrar al docente.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function actualizarPerfil(Request $request){
        $usuario = Auth::user();

        try {
            $validated = $request->validate([
                'nombres' => 'required|string|max:100',
                'ap_paterno' => 'required|string|max:100',
                'ap_materno' => 'nullable|string|max:100',
                'email' => 'required|email|unique:users,email,' . $usuario->pk_usuario . ',pk_usuario',
                'img_user' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            DB::beginTransaction();

            $usuario->nombres = $validated['nombres'];
            $usuario->ap_paterno = $validated['ap_paterno'];
            $usuario->ap_materno = $validated['ap_materno'] ?? '';
            $usuario->email = $validated['email'];

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
                'mensaje' => 'Perfil actualizado correctamente.',
                'ruta' => route('docente.perfil'),
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
                'mensaje' => 'Ocurrió un error al actualizar el perfil.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function detalleDocente($id){
        $docente = User::with(['gruposAsignados.grupo.carrera', 'gruposAsignados.materia', 'gruposAsignados.grupo.alumnos'])->findOrFail($id);

        $grupos_asignados = $docente->gruposAsignados->count();

        $estudiantes = 0; // debo arreglar esto

        $actividades = $docente->actividades->count() ?? 0;

        return view('coordinacion.detalle-docente', compact('docente', 'grupos_asignados', 'estudiantes', 'actividades'));
    }

    public function cargarDocente($id){
        $docente = User::findOrFail($id);
        return view('coordinacion.editar-docente', compact('docente'));
    }

    public function actualizarCorreo(Request $request){
        try {
            $validated = $request->validate([
                'pk_usuario' => 'required|integer',
                'email' => 'required|email|unique:users,email,' . $request->pk_usuario . ',pk_usuario',
            ], [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El formato del correo no es válido.',
                'email.unique' => 'El correo electrónico ya está registrado.',
            ]);

            $usuario = User::findOrFail($request->pk_usuario);

            DB::beginTransaction();

            $usuario->email = $validated['email'];
            $usuario->save();

            DB::commit();

            return response()->json([
                'mensaje' => 'Docente actualizado correctamente.',
                'ruta' => route('coordinacion.lista-docentes'),
                'class' => 'success'
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Ocurrió un error al actualizar al docente.',
                'detalle' => $th->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function panel(){
        try {
            $usuario = Auth::user();
            $id = $usuario->pk_usuario;

            $grupos = GrupoMateria::with(['grupo.carrera', 'materia'])
                ->with(['grupo' => function ($q) {
                    $q->withCount('alumnos');
                }])
                ->where('fk_docente', $id);

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
                ->with(['grupo.alumnos.usuario' => function($q) {
                    $q->where('fk_tipo_usuario', 1);
                }])
                ->get()
                ->flatMap(function($gm) {
                    return $gm->grupo->alumnos
                        ->map(fn($alumno) => $alumno->usuario)
                        ->filter();
                })
                ->unique('pk_usuario')
                ->count();

            $gruposCount = $grupos->count();
            $actividadesCount = $actividades->count();

            return view('docente.inicio', compact('gruposCount', 'actividadesCount', 'actividadesRevisionCount', 'alumnosCount'));


        } catch (\Throwable $th) {
           return back()->with('error', 'Ocurrió un problema al cargar el panel: ' . $th->getMessage());
        }
    }

    public function calificar(){
        try {
            $docente = Auth::user();
            $id = $docente->pk_usuario;

            $grupos = GrupoMateria::with('grupo', 'materia')
                                    ->where('fk_docente', $id)
                                    ->get();

            return view('docente.calificar', compact('grupos'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar los grupos: ' . $th->getMessage());
        }
    }

    public function calificarGrupo($id){
        try {
            $grupo = Grupo::with('carrera', 'grupoMaterias.materia')->findOrFail($id);
            $materia = $grupo->grupoMaterias->first()->materia;

            $alumnos = GrupoAlumno::with('alumno.usuario')
                                    ->where('fk_grupo', $id)
                                    ->get();

            $calificaciones = Calificaciones::where('fk_materia', $materia->pk_materia)->get();

            return view('docente.calificar-grupo', compact('grupo', 'alumnos', 'materia', 'calificaciones'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un problema al cargar los alumnos: ' . $th->getMessage());
        }
    }

    public function guardarCalificacion(Request $request){
        $request->validate([
            'fk_alumno' => 'required|integer',
            'fk_materia' => 'required|integer',
            'calificacion' => 'required|numeric|min:0|max:10',
        ],[
            'calificacion.min' => 'La calificación no puede ser menor a 0.',
            'calificacion.max' => 'La calificación no puede ser mayor a 10.',
            'calificacion.numeric' => 'La calificación debe ser un número válido.',
        ]);

        try {
            $calificacion = Calificaciones::where('fk_alumno', $request->fk_alumno)
                ->where('fk_materia', $request->fk_materia)
                ->first();

            if (!$calificacion) {
                Calificaciones::create([
                    'fk_alumno' => $request->fk_alumno,
                    'fk_materia' => $request->fk_materia,
                    'calificacion' => $request->calificacion,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Calificación guardada correctamente.'
                ]);
            }

            $calificacion->update([
                'calificacion' => $request->calificacion
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Calificación actualizada correctamente.'
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error al guardar la calificación.'
            ], 500);
        }
    }


}

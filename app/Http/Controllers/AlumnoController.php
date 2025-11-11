<?php

namespace App\Http\Controllers;

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

        $grupoAlumno = GrupoAlumno::where('fk_alumno', $alumno->pk_alumno)->first();

        $carrera = null;
        if($grupoAlumno) {
            $grupo = Grupo::find($grupoAlumno->fk_grupo);
            if($grupo) {
                $carrera = $grupo->carrera;
            }
        }

        $promedio = Calificaciones::where('fk_alumno', $alumno->pk_alumno)->avg('calificacion');

        return view('alumno.perfil', [
            'usuario' => $usuario,
            'carrera' => $carrera ? $carrera->nombre : 'Sin carrera',
            'promedio' => $promedio ?? 'N/A'
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
        $alumno = Alumno::with('usuario')->findOrFail($alumnoId);
        $grupo = Grupo::with('actividades')->findOrFail($grupoId);

        $actividades = $grupo->actividades->map(function($actividad) use ($alumno) {

            $entrega = RespuestasAlumno::where('fk_actividad', $actividad->pk_actividad)
                        ->where('fk_alumno', $alumno->pk_alumno)
                        ->first();

            $estado = 'Pendiente';

            if ($entrega) {
                $estado = 'Entregada';
            } elseif ($actividad->fecha_fin && now()->greaterThan($actividad->fecha_fin)) {
                $estado = 'Caducada';
            }

            return [
                'actividad' => $actividad,
                'estado' => $estado,
                'entrega' => $entrega
            ];
        });

        return view('docente.actividades-alumno', [
            'alumno' => $alumno,
            'grupo' => $grupo,
            'actividades' => $actividades
        ]);
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

}

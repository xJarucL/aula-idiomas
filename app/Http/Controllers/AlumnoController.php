<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Tipo_usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

}

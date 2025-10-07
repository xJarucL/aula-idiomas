<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Tipo_usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AlumnoController extends Controller
{
    public function listaAlumnos(){
        $alumnos = Alumno::with([
                'usuario',
                'grupos.grupo.carrera',
                'calificaciones'
            ])
            ->paginate(10);

        foreach ($alumnos as $alumno) {
            $promedio = $alumno->calificaciones->avg('calificacion');
            $alumno->promedio = $promedio ? number_format($promedio, 1) : 'Sin registro';
        }

        return view('coordinacion.lista-alumnos', compact('alumnos'));
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'matricula' => 'required|unique:users,matricula|max:20',
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

}

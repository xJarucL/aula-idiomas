<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CoordinadorController extends Controller
{
    public function inicio(){
        $gruposCount = Grupo::count();
        $docentesCount = User::where('fk_tipo_usuario', 2)->count();
        $alumnosCount = User::where('fk_tipo_usuario', 1)->count();

        return view('coordinacion.inicio', compact('gruposCount', 'docentesCount', 'alumnosCount'));
    }

    public function listaGrupos(){
        $grupos = Grupo::with([
            'carrera',
            'cuatrimestre'
        ])
        ->paginate(10);

        return view('coordinacion.lista-grupos', compact('grupos'));
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'email' => 'required|unique:users',
                'nombres' => 'required|string|max:100',
                'ap_paterno' => 'required|string|max:100',
                'ap_materno' => 'nullable|string|max:100',
                'img_user' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
                'ruta' => route('coordinacion.lista-docente'),
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

    public function guardarGrupo(Request $request){
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:1',
                'fk_carrera' => 'required|string|max:1',
                'fk_cuatrimestre' => 'required|string|max:2',
                'año' => 'required|string|max:4',
            ]);

            $grupo = Grupo::create([
                'nombre' => $validated['nombre'],
                'fk_carrera' => $validated['fk_carrera'],
                'fk_cuatrimestre' => $validated['fk_cuatrimestre'],
                'año' => $validated['año']
            ]);

            DB::commit();

            return response()->json([
                'mensaje' => 'Grupo registrado correctamente.',
                'ruta' => route('coordinacion.lista-grupos'),
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
                'mensaje' => 'Ocurrió un error al registrar el grupo.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

}

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
use Illuminate\Support\Str;

class CoordinadorController extends Controller
{
    public function inicio(){
        $gruposCount = Grupo::count();
        $docentesCount = User::where('fk_tipo_usuario', 2)->count();
        $alumnosCount = User::where('fk_tipo_usuario', 1)->count();
        $coordinadoresCount = User::where('fk_tipo_usuario', 3)->count();

        return view('coordinacion.inicio', compact('gruposCount', 'docentesCount', 'alumnosCount', 'coordinadoresCount'));
    }

    public function listaCoordinadores(Request $request){
        $query = User::where('fk_tipo_usuario', 3);

        if ($request->filled('search')) {
            $search = str_replace(' ', '', $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw("REPLACE(CONCAT(nombres, ap_paterno, ap_materno, email), ' ', '') LIKE ?", ["%{$search}%"])
                ->orWhere('nombres', 'like', "%{$search}%")
                ->orWhere('ap_paterno', 'like', "%{$search}%")
                ->orWhere('ap_materno', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $coordinadores = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('partials.tabla_coordinadores', compact('coordinadores'))->render();
        }

        return view('coordinacion.lista-coordinador', compact('coordinadores'));
    }

    public function listaCoordinadoresDeshabilitados(Request $request)
    {
        $query = User::onlyTrashed()->where('fk_tipo_usuario', 3);

        if ($request->filled('search')) {
            $search = str_replace(' ', '', $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw("REPLACE(CONCAT(nombres, ap_paterno, ap_materno, email), ' ', '') LIKE ?", ["%{$search}%"])
                ->orWhere('nombres', 'like', "%{$search}%")
                ->orWhere('ap_paterno', 'like', "%{$search}%")
                ->orWhere('ap_materno', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $coordinadores = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('partials.tabla_coordinadores', compact('coordinadores'))->render();
        }

        return view('coordinacion.lista-coordinador', compact('coordinadores'));
    }

    public function guardarCoordinador(Request $request){
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
                'fk_tipo_usuario' => 3,
            ]);

            DB::commit();

            return response()->json([
                'mensaje' => 'Coordinador registrado correctamente.',
                'ruta' => route('coordinacion.lista-coordinador'),
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
                'mensaje' => 'Ocurrió un error al registrar al coordinador.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function eliminarCoordinador($id){
        $coordinador = User::findOrFail($id);
        $coordinador->delete();

        return redirect()->route('coordinacion.lista-coordinador')->with('success', 'Coordinador deshabilitado correctamente.');
    }

    public function restaurarCoordinador($id){
        $coordinador = User::withTrashed()->findOrFail($id);
        $coordinador->restore();

        return redirect()->route('coordinacion.lista-coordinador-deshabilitados')->with('success', 'Coordinador restaurado correctamente.');
    }

    public function perfil(){
        return view('coordinacion.perfil');
    }

    public function actualizarPerfil(Request $request)
    {
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
                'ruta' => route('coordinador.perfil'),
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

    public function detalleCoordinador($id){
        $coordinador = User::findOrFail($id);
        return view('coordinacion.detalle-coordinador', compact('coordinador'));
    }


}

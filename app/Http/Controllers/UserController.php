<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tipo_usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'matricula' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = User::where('matricula', $request->matricula)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            Auth::login($usuario);

            return response()->json([
                'mensaje' => '¡Inicio de sesión exitoso!',
                'ruta' => route('alumno.inicio'),
                'class' => 'success'
            ]);

        } else {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas.',
                'class' => 'error'
            ], 422);
        }
    }

    public function logout(Request $request){
        $nombres = Auth::user() ? Auth::user()->username : 'Usuario no autenticado';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Cerraste sesión correctamente.');

    }

    public function cambiarTipo(Request $request){
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.']);
        }

        $user->fk_tipo_usuario = $request->tipo;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Tipo de usuario actualizado correctamente.']);
    }


}

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
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'matricula' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = User::where('matricula', $request->matricula)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            $token = $usuario->createToken('flutter_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombres,
                    'rol' => $usuario->fk_tipo_usuario,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cerraste sesión correctamente.'
        ]);
    }

    public function loginWithGoogle(Request $request){
        try {
            $email = $request->input('email');

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'El campo email es requerido.',
                ], 400);
            }

            $user = User::where('email', $email)->first();
            $token = $user->createToken('flutter_token')->plainTextToken;


            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autorizado.',
                ], 401);
            }

            if (!in_array($user->fk_tipo_usuario, [2, 3])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo docentes y coordinación pueden iniciar con Google.',
                ], 403);
            }

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso.',
                'token' => $token,
                'user' => [
                    'id' => $user->pk_usuario,
                    'nombre' => $user->nombres,
                    'email' => $user->email,
                    'rol' => $user->fk_tipo_usuario,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión con Google.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id){
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
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

        $usuario->nombres = $request->input('nombres');
        $usuario->ap_paterno = $request->input('ap_paterno');
        $usuario->ap_materno = $request->input('ap_materno');
        $usuario->email = $request->input('email');
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario,
        ]);
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{

    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Usuario no autorizado');
            }

            if (!in_array($user->fk_tipo_usuario, [2, 3])) {
                return redirect()->route('login')->with('error', 'Solo docentes y coordinación pueden iniciar con Google');
            }

            Auth::login($user);

            return match($user->fk_tipo_usuario) {
                2 => redirect()->route('docente.inicio'),
                3 => redirect()->route('coordinacion.inicio'),
                default => redirect()->route('login')->with('error', 'Rol no permitido'),
            };

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error al iniciar sesión con Google');
        }
    }

}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            switch ($user->fk_tipo_usuario) {
                case 1:
                    return redirect()->route('alumno.inicio');
                case 2:
                    return redirect()->route('docente.inicio');
                case 3:
                    return redirect()->route('coordinacion.inicio');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Tipo de usuario no válido.');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        if ($roles && !in_array($user->fk_tipo_usuario, $roles)) {
            return redirect()->route('login')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}

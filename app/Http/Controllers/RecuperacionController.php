<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudRecuperacion;

class RecuperacionController extends Controller
{
    public function enviarSolicitud(Request $request)
    {
        $request->validate([
            'matricula' => 'required|exists:users,matricula',
        ]);

        $usuario = User::where('matricula', $request->matricula)->first();

        $coordinadores = User::where('fk_tipo_usuario', 3)
                     ->whereNull('deleted_at')
                     ->get();

        foreach ($coordinadores as $coordinador) {
            if($coordinador->email) {
                Mail::to($coordinador->email)
                    ->send(new SolicitudRecuperacion($usuario));
            }
        }

        return view('reset.solicitud');
    }

    public function restablecer(User $usuario)
    {
        $usuario->password = Hash::make($usuario->matricula);
        $usuario->save();

        return view('reset.recuperacion-exitosa', ['usuario' => $usuario]);
    }
}

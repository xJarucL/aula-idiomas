<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudRecuperacion;

use App\Models\User;

class RecuperacionController extends Controller
{
    public function enviarSolicitud(Request $request){
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

        return response()->json([
            'success' => true,
            'message' => 'Tu coordinador ha sido avisado, espera su respuesta de reestablecimiento.',
        ]);
    }
}

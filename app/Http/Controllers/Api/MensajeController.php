<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mensajes;
use App\Models\User;

class MensajeController extends Controller
{
    public function enviar(Request $request){
        try {
            $request->validate([
                'de_usuario' => 'required|exists:users,pk_usuario',
                'para_usuario' => 'required|exists:users,pk_usuario',
                'mensaje' => 'required|string',
            ]);

            $mensaje = Mensajes::create([
                'de_usuario' => $request->de_usuario,
                'para_usuario' => $request->para_usuario,
                'mensaje' => $request->mensaje,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado.',
                'data' => $mensaje
            ]);
        } catch (\Throwable $th) {
           return response()->json([
            'success' => false,
            'message' => 'Algo salió mal...',
            'error' => $th
           ]);
        }
    }

    public function obtenerChats($id){
        try {
            $usuarios = Mensajes::where('de_usuario', $id)
                ->orWhere('para_usuario', $id)
                ->selectRaw("
                    CASE
                        WHEN de_usuario = ? THEN para_usuario
                        ELSE de_usuario
                    END AS usuario_chat
                ", [$id])
                ->groupBy('usuario_chat')
                ->pluck('usuario_chat');

            $listaContactos = User::whereIn('pk_usuario', $usuarios)->get();
            $usuariosGeneral = User::all();

            return response()->json([
                'success' => true,
                'message' => 'Contactos obtenidos.',
                'contactos' => $listaContactos,
                'usuarios' => $usuariosGeneral
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal',
                'error' => $th
            ]);
        }
    }

    public function obtenerMensajesChat($id_propio, $id_contacto){
        try {
            $mensajes = Mensajes::where(function ($q) use ($id_propio, $id_contacto) {
                $q->where('de_usuario', $id_propio)->where('para_usuario', $id_contacto);
            })
            ->orWhere(function ($q) use ($id_propio, $id_contacto) {
                $q->where('de_usuario', $id_contacto)->where('para_usuario', $id_propio);
            })
            ->orderBy('created_at', 'asc')
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Contactos obtenidos.',
                'data' => $mensajes,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal',
                'error' => $th
            ]);
        }
    }
}

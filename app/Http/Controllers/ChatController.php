<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensajes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function enviar(Request $request){
        try {
            $usuario = Auth::user()->pk_usuario;

            Mensajes::create([
                'de_usuario' => $usuario,
                'para_usuario' => $request->receptor,
                'mensaje' => $request->mensaje
            ]);

            return response()->json(['ok' => true]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Algo salió mal...',
                'error' => $th->getMessage()
            ]);
        }
    }

    public function obtenerChats(){
        try {
            $usuario = Auth::user();
            $id = $usuario->pk_usuario;

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

            $contactos = User::whereIn('pk_usuario', $usuarios)->get();

            return view('chat.inicio', [
                'contactos' => $contactos
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Ocurrió un error al obtener los usuarios',
                'detalle' => $th->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function obtenerUsuarios(Request $request){
        try {

            $usuarios = User::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $usuarios->where(function ($q) use ($search) {
                    $q->where('nombres', 'LIKE', "%$search%")
                    ->orWhere('ap_paterno', 'LIKE', "%$search%")
                    ->orWhere('ap_materno', 'LIKE', "%$search%");
                });
            }

            if ($request->filled('tipo')) {
                $usuarios->where('fk_tipo_usuario', $request->tipo);
            }

            $usuarios = $usuarios->get();

            if ($request->ajax()) {
                return view('chat.chat_usuarios_listado', compact('usuarios'))->render();
            }

            return view('chat.chat_usuarios', compact('usuarios'));

        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Ocurrió un error al obtener los usuarios',
                'detalle' => $th->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }


    public function obtenerMensajesChat($id){
        $usuario = Auth::user()->pk_usuario;

        $mensajes = Mensajes::
            where(function($q) use ($usuario, $id) {
                $q->where('de_usuario', $usuario)
                  ->where('para_usuario', $id);
            })
            ->orWhere(function($q) use ($usuario, $id) {
                $q->where('de_usuario', $id)
                  ->where('para_usuario', $usuario);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $contacto = User::findOrFail($id);

        $contactos = User::whereIn(
            'pk_usuario',
            Mensajes::where('de_usuario',$usuario)
                ->orWhere('para_usuario',$usuario)
                ->selectRaw("
                    CASE
                        WHEN de_usuario = ? THEN para_usuario
                        ELSE de_usuario
                    END AS usuario_chat
                ", [$usuario])
                ->groupBy('usuario_chat')
                ->pluck('usuario_chat')
        )->get();

        return view('chat.inicio', compact('mensajes', 'contacto', 'contactos'));
    }

    public function actualizarMensajes($contactoId){
        $usuario = Auth::user()->pk_usuario;

        $mensajes = Mensajes::
            where(function($q) use ($usuario, $contactoId) {
                $q->where('de_usuario', $usuario)
                  ->where('para_usuario', $contactoId);
            })
            ->orWhere(function($q) use ($usuario, $contactoId) {
                $q->where('de_usuario', $contactoId)
                  ->where('para_usuario', $usuario);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($mensajes);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Grupo;
use App\Models\User;
use App\Models\Mensajes;

class CoordinacionController extends Controller
{
    public function panel($id){
        try {
            $gruposCount = Grupo::count();
            $docentesCount = User::where('fk_tipo_usuario', 2)->count();
            $alumnosCount = User::where('fk_tipo_usuario', 1)->count();
            $coordinadoresCount = User::where('fk_tipo_usuario', 3)->count();

            $ultimoMensaje = Mensajes::with('paraUsuario')
                                    ->where('para_usuario', $id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();

            return response()->json([
                'success' => true,
                'message' => 'Datos Obtenidos correctamente',
                'gruposCount' => $gruposCount,
                'docentesCount' => $docentesCount,
                'alumnosCount' => $alumnosCount,
                'coordinadoresCount' => $coordinadoresCount,
                'ultimoMensaje' => $ultimoMensaje
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}

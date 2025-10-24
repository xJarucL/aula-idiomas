<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\GrupoMateria;

class GrupoController extends Controller
{
    public function listaGruposDocente(Request $request){
        $id = $request->pk_docente ?? auth()->user()->pk_usuario;

        $query = GrupoMateria::with(['grupo.carrera', 'materia'])
            ->where('fk_docente', $id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Grupos del docente cargados correctamente",
            'data' => $query
        ]);
    }
}

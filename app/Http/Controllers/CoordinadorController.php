<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grupo;

class CoordinadorController extends Controller
{
    public function inicio(){
        $gruposCount = Grupo::count();
        $docentesCount = User::where('fk_tipo_usuario', 2)->count();
        $alumnosCount = User::where('fk_tipo_usuario', 1)->count();

        return view('coordinacion.inicio', compact('gruposCount', 'docentesCount', 'alumnosCount'));
    }

    public function listaGrupos(){
        $grupos = Grupo::with([
            'carrera',
            'cuatrimestre'
        ])
        ->paginate(10);

        return view('coordinacion.lista-grupos', compact('grupos'));
    }

}

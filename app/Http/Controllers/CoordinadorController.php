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

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DocenteController extends Controller
{
    public function listaDocentes(){
        $docentes = User::where('fk_tipo_usuario', '=', 2)->paginate(10);

        return view('coordinacion.lista-docente', compact('docentes'));
    }
}

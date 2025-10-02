<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tipo_usuario;

class AlumnoController extends Controller
{
    public function listaAlumnos(){
        $usuarios = User::where('fk_tipo_usuario', 1)
                    ->orderBy('nombres', 'asc')
                    ->paginate(10);

        return view('coordinacion.lista-alumnos', compact('usuarios'));
    }
}

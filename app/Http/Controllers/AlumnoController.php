<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Tipo_usuario;

class AlumnoController extends Controller
{
    public function listaAlumnos(){
        $alumnos = Alumno::with([
                'usuario',
                'grupos.grupo.carrera',
                'calificaciones'
            ])
            ->paginate(10);

        foreach ($alumnos as $alumno) {
            $promedio = $alumno->calificaciones->avg('calificacion');
            $alumno->promedio = $promedio ? number_format($promedio, 1) : 'Sin registro';
        }

        return view('coordinacion.lista-alumnos', compact('alumnos'));
    }
}

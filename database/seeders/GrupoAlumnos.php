<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GrupoAlumno;

class GrupoAlumnos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GrupoAlumno::create([
            'fk_alumno' => '1',
            'fk_grupo' => '1',
        ]);
        GrupoAlumno::create([
            'fk_alumno' => '2',
            'fk_grupo' => '1',
        ]);
    }
}

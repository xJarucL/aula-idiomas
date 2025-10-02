<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Materia;

class Materias extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Materia::create([
            'nombre' => 'Inglés I',
        ]);
        Materia::create([
            'nombre' => 'Inglés II',
        ]);
        Materia::create([
            'nombre' => 'Inglés III',
        ]);
        Materia::create([
            'nombre' => 'Inglés IV',
        ]);
        Materia::create([
            'nombre' => 'Inglés V',
        ]);
        Materia::create([
            'nombre' => 'Inglés VI',
        ]);
        Materia::create([
            'nombre' => 'Inglés VII',
        ]);
        Materia::create([
            'nombre' => 'Inglés VIII',
        ]);
        Materia::create([
            'nombre' => 'Inglés IX',
        ]);
    }
}

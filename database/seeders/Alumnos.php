<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumno;

class Alumnos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Alumno::create([
            'fk_usuario' => '5',
        ]);
        Alumno::create([
            'fk_usuario' => '6',
        ]);
    }
}

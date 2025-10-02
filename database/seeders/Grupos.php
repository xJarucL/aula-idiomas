<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;

class Grupos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grupo::create([
            'nombre' => 'A',
            'año' => '2025',
            'fk_carrera' => '1',
            'fk_cuatrimestre' => '1',
        ]);
    }
}

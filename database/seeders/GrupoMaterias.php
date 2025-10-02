<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GrupoMateria;

class GrupoMaterias extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GrupoMateria::create([
            'fk_materia' => '1',
            'fk_grupo' => '1',
            'fk_docente' => '2'
        ]);
    }
}

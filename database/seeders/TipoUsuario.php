<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuario extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_usuario')->insert([
            'nom_tipo_usuario' => 'Alumno'
        ]);
        DB::table('tipo_usuario')->insert([
            'nom_tipo_usuario' => 'Docente'
        ]);
        DB::table('tipo_usuario')->insert([
            'nom_tipo_usuario' => 'Coordinador'
        ]);
    }
}

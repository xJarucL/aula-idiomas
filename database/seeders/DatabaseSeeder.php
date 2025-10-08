<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TipoUsuario::class);
        $this->call(Usuarios::class);
        $this->call(Alumnos::class);
        $this->call(Cuatrimestres::class);
        $this->call(Carreras::class);
        $this->call(Grupos::class);
        $this->call(GrupoAlumnos::class);
        $this->call(Materias::class);
        $this->call(GrupoMaterias::class);
    }
}

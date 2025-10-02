<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;

class Carreras extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrera::create([
            'nombre' => 'Ingeniería en Tecnologías de la Información e Innovación Digital',
            'abreviatura' => 'ITIID',
        ]);
        Carrera::create([
            'nombre' => 'Licenciatura en Contaduría',
            'abreviatura' => 'LC',
        ]);
        Carrera::create([
            'nombre' => 'Ingeniería en Agricultura Sustentable y Protegida',
            'abreviatura' => 'IASP',
        ]);
        Carrera::create([
            'nombre' => 'Licenciatura en Enfermería',
            'abreviatura' => 'LE',
        ]);
        Carrera::create([
            'nombre' => 'Licenciatura en Gastronomía',
            'abreviatura' => 'LG',
        ]);
        Carrera::create([
            'nombre' => 'Ingeniería en Mantenimiento Industrial',
            'abreviatura' => 'IMI',
        ]);
        Carrera::create([
            'nombre' => 'Ingeniería en Mecatrónica',
            'abreviatura' => 'IM',
        ]);
        Carrera::create([
            'nombre' => 'Ingeniería en Alimentos',
            'abreviatura' => 'IA',
        ]);
        Carrera::create([
            'nombre' => 'Licenciatura en Gestión y Desarrollo Turístico',
            'abreviatura' => 'LGDT',
        ]);
    }
}

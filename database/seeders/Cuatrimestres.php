<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cuatrimestre;

class Cuatrimestres extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cuatrimestre::create([
            'num_cuatri' => '1',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '2',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '3',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '4',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '5',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '6',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '7',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '8',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '9',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '10',
        ]);
        Cuatrimestre::create([
            'num_cuatri' => '11',
        ]);

    }
}

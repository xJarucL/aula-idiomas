<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Usuarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombres' => 'María',
            'ap_paterno' => 'Angeles',
            'ap_materno' => null,
            'matricula' => null,
            'email' => 'jaruny.cl@gmail.com',
            'password' => Hash::make('maria'),
            'fk_tipo_usuario' => 3
        ]);

        User::create([
            'nombres' => 'Roberto',
            'ap_paterno' => 'Gonzales',
            'ap_materno' => null,
            'matricula' => null,
            'email' => 'jarunyta1096@gmail.com',
            'password' => Hash::make('roberto'),
            'fk_tipo_usuario' => 2
        ]);

        User::create([
            'nombres' => 'Sergio',
            'ap_paterno' => 'Moles',
            'ap_materno' => 'Montes',
            'matricula' => '202200096',
            'email' => null,
            'password' => Hash::make('202200096'),
            'fk_tipo_usuario' => 1
        ]);

        ser::create([
            'nombres' => 'Ariel',
            'ap_paterno' => 'Salazar',
            'ap_materno' => null,
            'matricula' => null,
            'email' => 'aasm052002@gmail.com',
            'password' => Hash::make('ariel'),
            'fk_tipo_usuario' => 3
        ]);

        User::create([
            'nombres' => 'Ángel',
            'ap_paterno' => 'Medina',
            'ap_materno' => null,
            'matricula' => null,
            'email' => 'angelariel6860@gmail.com',
            'password' => Hash::make('angel'),
            'fk_tipo_usuario' => 2
        ]);
    }
}

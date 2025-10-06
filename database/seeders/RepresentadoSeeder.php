<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Representado;
use App\Models\User;

class RepresentadoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        $representados = [
            [
                'ci' => '12345678901',
                'nombres' => 'Carlos Alberto',
                'apellidos' => 'González Pérez',
                'fecha_nacimiento' => '2015-03-15',
                'telefono' => '04141234567',
                'direccion' => 'Av. Principal de La California, Edificio Las Acacias, Piso 3, Apartamento 3-A, Caracas',
                'nivel_academico' => 'Primaria',
                'user_id' => 2, // Roberto González
            ],
            // Actualizar los demás representados con user_id en lugar de representante_id
        ];

        foreach ($representados as $representado) {
            Representado::create($representado);
        }
    }
}
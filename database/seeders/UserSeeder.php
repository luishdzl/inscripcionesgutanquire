<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@inscripcion.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Usuarios representantes
        $representantes = [
            [
                'name' => 'Roberto González',
                'email' => 'roberto@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'ci' => '12345678',
                'nombres' => 'Roberto',
                'apellidos' => 'González Méndez',
                'telefono' => '04141234567',
                'direccion' => 'Av. Principal de La California, Edificio Las Acacias, Piso 3, Apartamento 3-A, Caracas',
                'fecha_nacimiento' => '1980-05-15',
                'vive_con_representado' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana Rodríguez',
                'email' => 'ana@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'ci' => '87654321',
                'nombres' => 'Ana Teresa',
                'apellidos' => 'Rodríguez de Pérez',
                'telefono' => '04241234567',
                'direccion' => 'Calle 72 con Av. 3H, Edificio Don Luis, Piso 2, Apartamento 2-B, Maracaibo',
                'fecha_nacimiento' => '1975-08-22',
                'vive_con_representado' => false,
                'email_verified_at' => now(),
            ],
            // Agregar más representantes aquí...
        ];

        foreach ($representantes as $representante) {
            User::create($representante);
        }

        // Crear más usuarios de prueba
        User::factory(5)->create();
    }
}
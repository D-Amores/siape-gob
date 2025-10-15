<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar que existan algunas áreas
        $areas = DB::table('areas')->pluck('id', 'name');
        if ($areas->isEmpty()) {
            DB::table('areas')->insert([
                ['name' => 'Recursos Humanos', 'manager_name' => 'María Pérez', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Sistemas', 'manager_name' => 'Carlos López', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Finanzas', 'manager_name' => 'Ana Torres', 'created_at' => now(), 'updated_at' => now()],
            ]);
            $areas = DB::table('areas')->pluck('id', 'name');
        }

        // Obtener IDs de las áreas
        $areaIds = $areas->values()->toArray();

        // Datos de ejemplo
        $personnel = [
            [
                'name' => 'José',
                'middle_name' => 'Luis',
                'last_name' => 'Martínez',
                'phone' => '9991234567',
                'email' => 'jose.martinez@example.com',
                'is_active' => true,
                'area_id' => 1,
            ],
            [
                'name' => 'Ana',
                'middle_name' => 'María',
                'last_name' => 'Gómez',
                'phone' => '9997654321',
                'email' => 'ana.gomez@example.com',
                'is_active' => true,
                'area_id' => 1,
            ],
            [
                'name' => 'Carlos',
                'middle_name' => 'Eduardo',
                'last_name' => 'Ramírez',
                'phone' => '9988889999',
                'email' => 'carlos.ramirez@example.com',
                'is_active' => false,
                'area_id' => 1,
            ],
            [
                'name' => 'Laura',
                'middle_name' => 'Beatriz',
                'last_name' => 'Hernández',
                'phone' => '9977776666',
                'email' => 'laura.hernandez@example.com',
                'is_active' => true,
                'area_id' => 1,
            ],
            [
                'name' => 'Miguel',
                'middle_name' => 'Ángel',
                'last_name' => 'Castillo',
                'phone' => '9966665555',
                'email' => 'miguel.castillo@example.com',
                'is_active' => false,
                'area_id' => 1,
            ],
        ];

        foreach ($personnel as &$person) {
            $person['created_at'] = now();
            $person['updated_at'] = now();
        }

        DB::table('personnel')->insert($personnel);
    }
}

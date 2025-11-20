<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            /**
             * Estados de activos
             */
            ['name' => 'NUEVO', 'description' => 'El activo es nuevo y no ha sido utilizado.'],
            ['name' => 'DISPONIBLE', 'description' => 'El activo está disponible para su uso.'],
            ['name' => 'EN MANTENIMIENTO', 'description' => 'El activo está actualmente en mantenimiento.'],
            ['name' => 'FUERA DE SERVICIO', 'description' => 'El activo no está en condiciones de ser utilizado.'],
            /**
             * Estados de reportes de mantenimiento
             */
            ['name' => 'ABIERTO', 'description' => 'El seguimiento del mantenimiento está en proceso.'],
            ['name' => 'EN SEGUIMIENTO', 'description' => 'El activo está en seguimiento.'],
            ['name' => 'FINALIZANDO', 'description' => 'El seguimiento del mantenimiento está finalizando.'],
            ['name' => 'COMPLETADO', 'description' => 'El seguimiento del mantenimiento ha sido completado.'],
        ];

        foreach ($statuses as $statusData) {
            Status::firstOrCreate(['name' => $statusData['name']], $statusData);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar la caché de roles y permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir los roles del sistema
        $roles = [
            'admin',
            'assigner',
            'user',
        ];

        // Crear los roles si no existen
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->command->info('Roles creados: ' . implode(', ', $roles));
    }
}

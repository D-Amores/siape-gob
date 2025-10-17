<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Personnel;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarnos de que el rol 'admin' existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear el registro del personal
        $personnel = Personnel::firstOrCreate(
            ['email' => 'admin@example.com'], // evita duplicados si corres el seeder varias veces
            [
                'name' => 'Administrador',
                'last_name' => 'General',
                'middle_name' => null,
                'phone' => null,
                'is_active' => true,
                'area_id' => 17,
            ]
        );

        // Crear el usuario asociado al personal
        $user = User::firstOrCreate(
            ['username' => 'adminGob'],
            [
                'password' => Hash::make('admin12345'), // 🔒 cámbialo después
                'personnel_id' => $personnel->id,
                'area_id' => $personnel->area_id,
                'is_active' => $personnel->is_active,
            ]
        );

        // Asignar el rol de administrador
        if (!$user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        $this->command->info('✅ Administrador creado: admin / admin123');
    }
}

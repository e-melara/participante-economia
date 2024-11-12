<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $participante = Role::create(['name' => 'Participante']);
        $administrativo = Role::create(['name' => 'Administrador']);

        // Crear permisos
        Permission::create(['name' => 'user.perfil.view']);
        Permission::create(['name' => 'user.perfil.destroy']);
        Permission::create(['name' => 'user.perfil.update']);

        Permission::create(['name' => 'participante.view.store']);
        Permission::create(['name' => 'participante.view.update']);

        Permission::create(['name' => 'administrativo.participante.view']);
        Permission::create(['name' => 'administrativo.participante.update']);
        Permission::create(['name' => 'administrativo.participante.aprobar']);

        $administrativo->givePermissionTo([
            'user.perfil.view',
            'user.perfil.destroy',
            'user.perfil.update',
            'administrativo.participante.view',
            'administrativo.participante.update',
            'administrativo.participante.aprobar',
        ]);

        $participante->givePermissionTo([
            'user.perfil.view',
            'user.perfil.destroy',
            'user.perfil.update',
            'participante.view.store',
            'participante.view.update',
        ]);

        $user = User::find(1);
        $user->assignRole('Administrador');
    }
}

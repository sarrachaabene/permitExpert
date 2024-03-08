<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        
        // Créer les permissions
        $createExamPermission = Permission::firstOrCreate(['name' => 'create Examen']);
        $editExamPermission = Permission::firstOrCreate(['name' => 'edit Examen']);
        $deleteExamPermission = Permission::firstOrCreate(['name' => 'delete Examen']);
        //Define Roles

        $superadminRole = Role::firstOrCreate(['name' => 'super admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $secretaireRole = Role::firstOrCreate(['name' => 'secretaire']);
        $moniteurRole = Role::firstOrCreate(['name' => 'moniteur']);
        $candidatRole = Role::firstOrCreate(['name' => 'candidat']);
        // Associer les permissions aux rôles
        $adminRole->givePermissionTo($createExamPermission, $editExamPermission, $deleteExamPermission);

        // Associer les rôles aux utilisateurs dans le seeder UserSeeder
    }
}

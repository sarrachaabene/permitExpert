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
         Role::firstOrCreate(['name' => 'superAdmin']);
         Role::firstOrCreate(['name' => 'admin']);
         Role::firstOrCreate(['name' => 'secretaire']);
         Role::firstOrCreate(['name' => 'moniteur']);
         Role::firstOrCreate(['name' => 'candidat']);
         Role::firstOrCreate(['name' => 'visiteur']);
    }
}
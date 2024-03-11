<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $user =   User::create([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => Hash::make('123456789'),
        'role' => 'admin',
        'cin' => '123456789',
        'prenom' => 'John',
        'numTel' => '+1234567890',
        'dateNaissance' => '1990-01-01',
    ]);
    $adminRole = Role::where('name', 'admin')->first();
    $user->assignRole($adminRole);
    }
}

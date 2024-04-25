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
        'user_name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => Hash::make('123456789'),
        'role' => 'superAdmin',
        'cin' => '123456789',
        'numTel' => '+1234567890',
        'dateNaissance' => '1990-01-01',
    ]);
   $role=Role::where('name', 'superAdmin')->first();
    $user->assignRole($role);
    }
}
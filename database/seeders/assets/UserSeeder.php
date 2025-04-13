<?php

namespace Database\Seeders\assets;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Define user passwords
        $password  = Hash::make('admin123'); 
        $password1 = Hash::make('supervisor123'); 
        $password2 = Hash::make('petugas123'); 
        $teknisi   = Hash::make('teknisi123'); 
        $password3 = Hash::make('pengguna123'); 

        // Create Admin user
        $adminUser = User::create([
            'name'              => 'Admin',
            'email'             => 'admin@gmail.com',
            'password'          => $password,
            'email_verified_at' => now(),
        ]);
        
        // Create Supervisor user
        $supervisorUser = User::create([
            'name'              => 'Willy',
            'email'             => 'supervisor@gmail.com',
            'password'          => $password1,
            'email_verified_at' => now(),
        ]);
        
        // Create Petugas user
        $petugasUser = User::create([
            'name'              => 'Alvin',
            'email'             => 'petugas@gmail.com',
            'password'          => $password2,
            'email_verified_at' => now(),
        ]);

        $teknisiUser = User::create([
            'name'              => 'Tekno',
            'email'             => 'teknisi@gmail.com',
            'password'          => $teknisi,
            'email_verified_at' => now(),
        ]);
        
        // Create Regular User
        $penggunaUser = User::create([
            'name'              => 'Aziz',
            'email'             => 'user@gmail.com',
            'password'          => $password3,
            'email_verified_at' => now(),
        ]);

        // Assign roles to the users
        $adminRole      = Role::where('name', 'admin')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $petugasRole    = Role::where('name', 'petugas')->first();
        $teknisiRole    = Role::where('name', 'teknisi')->first();
        $userRole       = Role::where('name', 'pengguna')->first();

        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }

        if ($supervisorRole) {
            $supervisorUser->assignRole($supervisorRole);
        }

        if ($petugasRole) {
            $petugasUser->assignRole($petugasRole);
        }

        if ($teknisiRole) {
            $teknisiUser->assignRole($teknisiRole);
        }

        if ($userRole) {
            $penggunaUser->assignRole($userRole);
        }

        $this->command->info('Users created and roles assigned!');
    }
}

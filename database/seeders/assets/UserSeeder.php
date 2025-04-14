<?php

namespace Database\Seeders\assets;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $administrator = DB::table('branches')->where('branchName', 'Administrator')->first();
        $antapaniBranch = DB::table('branches')->where('branchName', 'Antapani')->first();
        $kiaraCondongBranch = DB::table('branches')->where('branchName', 'Kiara Condong')->first();
        $soekarnoHattaBranch = DB::table('branches')->where('branchName', 'Soekarno Hatta')->first();

        $password  = Hash::make('admin123'); 
        $password1 = Hash::make('supervisor123'); 
        $password2 = Hash::make('petugas123'); 
        $owner   = Hash::make('owner'); 
        $password3 = Hash::make('pengguna123'); 

        $adminUser = User::create([
            'name'              => 'Admin',
            'email'             => 'admin@gmail.com',
            'password'          => $password,
            'email_verified_at' => now(),  
            'branch_id'         => $administrator->branchId,  
        ]);
        
        $supervisorUser = User::create([
            'name'              => 'Willy',
            'email'             => 'supervisor@gmail.com',
            'password'          => $password1,
            'email_verified_at' => now(),
            'branch_id'         => $kiaraCondongBranch->branchId,  
        ]);
        
        $petugasUser = User::create([
            'name'              => 'Alvin',
            'email'             => 'petugas@gmail.com',
            'password'          => $password2,
            'email_verified_at' => now(),
            'branch_id'         => $soekarnoHattaBranch->branchId,  
        ]);

        $ownerUser = User::create([
            'name'              => 'Owner',
            'email'             => 'owner@gmail.com',
            'password'          => $owner,
            'email_verified_at' => now(),
            'branch_id'         => $antapaniBranch->branchId,  
        ]);
        
        $penggunaUser = User::create([
            'name'              => 'Aziz',
            'email'             => 'user@gmail.com',
            'password'          => $password3,
            'email_verified_at' => now(),
            'branch_id'         => $kiaraCondongBranch->branchId,  
        ]);

        $adminRole      = Role::where('name', 'admin')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $petugasRole    = Role::where('name', 'petugas')->first();
        $ownerRole    = Role::where('name', 'owner')->first();
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

        if ($ownerRole) {
            $ownerUser->assignRole($ownerRole);
        }

        if ($userRole) {
            $penggunaUser->assignRole($userRole);
        }

        $this->command->info('Users created and roles assigned!');
    }
}

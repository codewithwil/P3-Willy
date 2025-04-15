<?php

namespace Database\Seeders\assets;

use App\Models\People\Admin\Admin;
use App\Models\People\Employee\Employee;
use App\Models\People\Owner\Owner;
use App\Models\People\Supervisor\Supervisor;
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
            'email'             => 'admin@gmail.com',
            'password'          => $password,
            'email_verified_at' => now(),     
        ]);
        
        Admin::create([
            'user_id' => $adminUser->id,
            'name'    => 'Admin Utama',
            'telepon' => '08123456789',
            'foto'    => 'default.jpg',
        ]);
        
        
        $supervisorUser = User::create([
            'email'             => 'supervisor@gmail.com',
            'password'          => $password1,
            'email_verified_at' => now(),
            'branch_id'         => $kiaraCondongBranch->branchId,  
        ]);

        Supervisor::create([
            'user_id' => $supervisorUser->id,
            'name'    => 'supervisor mantap',
            'telepon' => '08123456789',
            'foto'    => 'default.jpg',
        ]);
        
        $petugasUser = User::create([
            'email'             => 'petugas@gmail.com',
            'password'          => $password2,
            'email_verified_at' => now(),
            'branch_id'         => $soekarnoHattaBranch->branchId,  
        ]);

        Employee::create([
            'user_id'    => $petugasUser->id,
            'name'       => 'petugas mantap',
            'telepon'    => '08123456789',
            'foto'       => 'default.jpg',
            'address'    => 'jalan doang ga jadian',
            'birthdate'  => '1990-01-01', 
            'hire_date'  => '2025-04-15', 
            'salary'     => 5000000,      
            'gender'     => 0,
            'status'     => 1      
        ]);
        

        $ownerUser = User::create([
            'email'             => 'owner@gmail.com',
            'password'          => $owner,
            'email_verified_at' => now(),
            'branch_id'         => $antapaniBranch->branchId,  
        ]);

        Owner::create([
            'user_id' => $ownerUser->id,
            'name'    => 'owner mantap',
            'telepon' => '08123456789',
            'foto'    => 'default.jpg',
            'address'    => 'kepo',
        ]);
        
        $penggunaUser = User::create([
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

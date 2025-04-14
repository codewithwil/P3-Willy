<?php

namespace Database\Seeders\assets;

use Illuminate\{
    Database\Seeder,
    Support\Facades\DB,
    Support\Facades\Hash,
};
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'admin',
            'supervisor',
            'petugas',
            'pengguna',
            'owner',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->command->info('Roles seeded successfully!');
    }
}

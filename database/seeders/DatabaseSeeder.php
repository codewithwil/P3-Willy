<?php

namespace Database\Seeders;

use Database\{
    Seeders\assets\UserSeeder,
    Seeders\assets\RolesSeeder,

};
use Database\Seeders\assets\BranchSeeder;
use Database\Seeders\assets\CompanySeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\{
    Database\Seeder,
    Support\Facades\DB
};
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $this->call([
                CompanySeeder::class,  
                BranchSeeder::class,  
                RolesSeeder::class,  
                UserSeeder::class,  
            ]);
                DB::commit();
        } catch (\Throwable $th) {
        DB::rollBack();
        dd($th);
        }
    
    }
}

<?php

namespace Database\Seeders;

use Database\{
    Seeders\assets\UserSeeder,
    Seeders\assets\RolesSeeder,

};

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

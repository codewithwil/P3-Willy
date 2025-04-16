<?php

namespace Database\Seeders\assets;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {

        $company = DB::table('companies')->where('name', 'LaundryKu')->first();
        DB::table('branches')->insert([
            [
                'company_id' => $company->companyId,
                'address' => 'Jalan Antapani No. 123, Bandung, Jawa Barat',
                'email' => 'antapani@branch.com',
                'operationalHours' => '08:00 - 17:00',
                'phone' => '0221234567',
                'ltd' => -6.910000,  
                'lng' => 107.640000, 
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company->companyId,
                'address' => 'Jalan Kiara Condong No. 456, Bandung, Jawa Barat',
                'email' => 'kiaracondong@branch.com',
                'operationalHours' => '08:00 - 18:00',
                'phone' => '0227654321',
                'ltd' => -6.925000,  
                'lng' => 107.610000, 
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company->companyId,
                'address' => 'Jalan Soekarno Hatta No. 789, Bandung, Jawa Barat',
                'email' => 'soekarnohatta@branch.com',
                'operationalHours' => '08:00 - 20:00',
                'phone' => '0229876543',
                'ltd' => -6.940000,  
                'lng' => 107.650000, 
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

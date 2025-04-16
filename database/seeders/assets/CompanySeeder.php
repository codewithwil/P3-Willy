<?php

namespace Database\Seeders\assets;

use Illuminate\Database\Seeder;
use App\Models\Resources\Company\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'companyId' => 1,
                'image'     => 'company1.png',
                'name'      => 'LaundryKu',
                'web'       => 'https://nusantaratech.co.id',
            ],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['companyId' => $company['companyId']],
                $company
            );
        }
    }
}

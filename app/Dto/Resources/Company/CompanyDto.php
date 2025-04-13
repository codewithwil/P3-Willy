<?php

namespace App\Dto\Resources\Company;

class CompanyDto
{
    public function __construct(
        public int $company_id,
        public string $files_id,
        public int $province_id,
        public int $regency_id,
        public int $district_id,
        public int $village_id,
        public string $name,
        public string $email,
        public string $phone,
        public string $address,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(...$request->only([
            'company_id', 'files_id', 'province_id', 
            'regency_id', 'district_id', 'village_id', 
            'name', 'email', 'phone', 'address'
        ]));
    }
}

<?php

namespace App\Services\Resources\Company;

use App\{
    Repositories\Contracts\Resources\Company\CompanyRepositoryContract,
    Traits\DbBeginTransac,
    Traits\responseMessage
};

use Illuminate\Http\Request;

class CompanyService{
    use DbBeginTransac, responseMessage;

    public function __construct(protected CompanyRepositoryContract $companyRepo){}

    public function index()
    {
        $company = $this->companyRepo->index();
        $provinces = $this->getProvinces();
        $regencies = $company ? $this->getRegencies($company->province_id) : [];
        $district = $company ? $this->getDistrict($company->regency_id) : [];
        $village = $company ? $this->getVillages($company->district_id) : [];
    
        return compact('company', 'provinces', 'regencies', 'district', 'village');
    }
    

    public function storeOrUpdate(Request $req, int $company_id = null): array{
        $validateData = $req->validate([
            'files_id'     => 'nullable|string|min:36|max:36', 
            'province_id'  => 'nullable|integer', 
            'regency_id'   => 'nullable|integer', 
            'disctrict_id' => 'nullable|integer',
            'village_id'   => 'nullable|integer',
            'name'         => 'required|string|min:3|max:50',
            'email'        => 'nullable|email|min:3|max:50|unique:companies,email',
            'phone'        => 'nullable|string|min:10|max:16',
            'address'      => 'nullable|string|min:3|max:255',
            'file'         => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);
       try {
        $company = $this->executeTransaction(function () use ($validateData, $company_id){
            return $this->companyRepo->saveCompany($validateData, $company_id);
        });
        $message = $company_id ? 'Company updated successfully.' : 'Company created successfully.';
        return $this->successMessage($message, $company);

       } catch (\Exception $e) {
           return $this->errorResponse('An Error occured'. $e->getMessage());
       }
    }
    
    private function getProvinces(){
        return json_decode(file_get_contents('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'));
    }

    private function getRegencies($provinceId){
        return json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json"));
    }

    private function getDistrict($regencyId){
        return json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$regencyId}.json"));
    }

    private function getVillages($districtId){
        return json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json"));
    }
}
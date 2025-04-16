<?php

namespace App\Http\Controllers\API\Resources\Company;

use App\{
    Http\Controllers\Controller,
    Services\Resources\Company\CompanyService
};
use App\Models\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isArray;

class CompanyC extends Controller
{

    public function index(){
        $company = Company::first();
        return view('admin.resources.company.index', compact('company'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'  => 'required|string|max:255',
            'web'   => 'nullable|string|max:75',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
    
        try {
            $company = Company::first();
            if (!$company) {
                $company = new Company($validatedData);
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $filePath = 'images/company/' . $fileName;
                    $file->move(public_path('images/company'), $fileName);
                    $company->image = $filePath;
                }
    
                $company->save();
            } else {
                if ($request->hasFile('image')) {
                    if ($company->image) {
                        $oldImagePath = public_path($company->image);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); 
                        }
                    }
                    $file = $request->file('image');
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $filePath = 'images/company/' . $fileName;
    
                    $file->move(public_path('images/company'), $fileName);
    
                    $validatedData['image'] = $filePath;
                }
                $company->update($validatedData);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data perusahaan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }
    
    
    
}

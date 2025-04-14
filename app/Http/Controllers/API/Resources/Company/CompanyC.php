<?php

namespace App\Http\Controllers\API\Resources\Company;

use App\{
    Http\Controllers\Controller,
    Services\Resources\Company\CompanyService
};
use App\Models\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isArray;

class CompanyC extends Controller
{

    public function index()
    {
        if (Auth::user()->branch && Auth::user()->branch->branchName === 'Administrator') {
            // Jika iya, tampilkan semua perusahaan
            $companies = Company::with('branch')->get();
        } else {
            // Jika tidak, hanya tampilkan perusahaan berdasarkan branch yang dimiliki 
            $companies = Company::where('branch_id', Auth::user()->branch_id)->get();
        }
    
        return view('admin.resources.company.index', compact('companies'));
    }
    



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:500',
        ]);
        
        $validatedData['branch_id'] = Auth::user()->branch_id;

        DB::beginTransaction();
    
        try {
            $company = Company::where('branch_id', Auth::user()->branch_id)->first();
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

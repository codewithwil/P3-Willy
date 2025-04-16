<?php

namespace App\Http\Controllers\API\Resources\Branch;

use App\Http\Controllers\Controller;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchC extends Controller
{
    public function index()
    {
        $branch = Branch::where('status', Branch::STATUS_ACTIVE)->get();
        return view('admin.resources.branch.index', compact('branch'));
    }

    public function invoice(){
        $branch    = Branch::where('status', Branch::STATUS_ACTIVE)->get();
        $company = Company::first();
        return view('admin.resources.branch.invoice', compact('branch', 'company'));
    }
    public function create()
    {
        $company = Company::first();
        return view('admin.resources.branch.create', compact('company'));
    }

    public function edit($branchId)
    {
        $company = Company::first();
        $branch = Branch::findOrFail($branchId);
        return view('admin.resources.branch.update', compact('branch', 'company'));
    }


    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'company_id'        => 'required',
                'address'           => 'required|string|min:3|max:255',
                'email'             => 'required|string|min:3|max:75',
                'operationalHours'  => 'required|string|min:1|max:50',
                'phone'             => 'required|digits_between:6,15',
                'ltd'               => 'required',  
                'lng'               => 'required',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $branch = Branch::create([
                'company_id'       => $req->input('company_id'),
                'address'          => $req->input('address'),
                'email'            => $req->input('email'),
                'operationalHours' => $req->input('operationalHours'),
                'phone'            => $req->input('phone'),
                'ltd'              => $req->input('ltd'),
                'lng'              => $req->input('lng'),
            ]);
    
            DB::commit();
    
            return redirect('/setting/branch/')->with('success', 'Cabang berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function update(Request $req, $branchId)
    {
        $req->validate([
            'company_id'            => 'nullable',
            'addressEdit'           => 'nullable|string|min:3|max:255',
            'emailEdit'             => 'nullable|string|min:3|max:75',
            'operationalHoursEdit'  => 'nullable|string|min:1|max:50',
            'phoneEdit'             => 'nullable|digits_between:6,15',
            'ltdEdit'               => 'nullable',  
            'lngEdit'               => 'nullable',
        ]);
        
        DB::beginTransaction();
        try {
            $branch                   = Branch::findOrFail($branchId);
            $branch->company_id       = $req->company_id;
            $branch->address          = $req->addressEdit;
            $branch->email            = $req->emailEdit;
            $branch->operationalHours = $req->operationalHoursEdit;
            $branch->phone            = $req->phoneEdit;
            $branch->ltd              = $req->ltdEdit;
            $branch->lng              = $req->lngEdit;

            $branch->save();

            DB::commit();

            return redirect('/setting/branch/')->with('success', 'Data cabang berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($branchId) {
        DB::beginTransaction();  
    
        try {
            $branch         = Branch::findOrFail($branchId);
            $branch->status = Branch::STATUS_INACTIVE;
            $branch->save();
            DB::commit();  
    
            $message = 'Data Cabang Berhasil Dihapus';  
            return redirect('/setting/branch/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/setting/branch/')
                ->with('error', 'Cabang tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

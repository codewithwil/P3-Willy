<?php

namespace App\Http\Controllers\API\Resources\Merk;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Resources\Merk\BrandMotor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BrandMotorC extends Controller
{
    public function index(){
        $brandMoto = BrandMotor::where('status', BrandMotor::STATUS_ACTIVE)->get();
        return view('admin.resources.brandMoto.index', compact('brandMoto'));
    }


    public function create(){
        return view('admin.resources.brandMoto.create');
    }

    public function invoice(){
        $brandMoto = BrandMotor::where('status', 1)->get();
        $company = Company::first();
        return view('admin.resources.brandMoto.invoice', compact('brandMoto', 'company'));
    }

    public function edit($brandMotorId){
        $brandMoto = BrandMotor::findOrFail($brandMotorId);
        return view('admin.resources.brandMoto.update', compact('brandMoto'));
    }

    public function store(Request $req)
    {
        // dd($req->all());
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'name'  => 'required|string|min:3|max:50',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }

    
            $brandMoto = BrandMotor::create([
                'name'  => $req->input('name'),
            ]);
    
            DB::commit();
    
            return redirect('/resources/brandMotor/')->with('success', 'Merk Motor berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function update(Request $req, $brandMotorId)
    {
        // dd($req->all());
        $req->validate([
            'name'  => 'nullable|string|min:3|max:50',
        ]);
        DB::beginTransaction();
        try {
            $brandMoto             = BrandMotor::findOrFail($brandMotorId);
            $brandMoto->name  = $req->name;
            $brandMoto->save();

            DB::commit();

            return redirect('/resources/brandMotor/')->with('success', 'Data Merk Motor berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($brandMotorId) {
        DB::beginTransaction();  
    
        try {
            $brandMoto         = BrandMotor::findOrFail($brandMotorId);
            $brandMoto->status = BrandMotor::STATUS_INACTIVE;
            $brandMoto->save();
            DB::commit();  
    
            $message = 'Data Merk Motor Berhasil Dihapus';  
            return redirect('/resources/brandMotor/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/resources/brandMotor/')
                ->with('error', 'Merk Motor tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

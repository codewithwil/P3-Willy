<?php

namespace App\Http\Controllers\API\Resources\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Resources\Supplier\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierC extends Controller
{
    
    public function index(){
        $supplier = Supplier::where('status', Supplier::STATUS_ACTIVE)->get();
        return view('admin.resources.supplier.index', compact('supplier'));
    }

    public function invoice(){
        $supplier = Supplier::where('status', Supplier::STATUS_ACTIVE)->get();
        $company  = Company::first();
        return view('admin.resources.supplier.invoice', compact('supplier', 'company'));
    }

    public function create(){
        return view('admin.resources.supplier.create');
    }

    public function edit($supplierId){
        $supplier = Supplier::findOrFail($supplierId);
        return view('admin.resources.supplier.update', compact('supplier'));
    }

    public function store(Request $req){
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($req->all(), [
                'name'    => 'required|string|min:3|max:50',
                'email'   => 'required|string|min:3|max:50|unique:suppliers,email',
                'phone'   => 'required|integer',
                'address' => 'required|string|min:3|max:255',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator)->withInput();
            }
            $supplier = Supplier::create([
                'name'    => $req->input('name'),
                'email'   => $req->input('email'),
                'phone'   => $req->input('phone'),
                'address' => $req->input('address'),
            ]);;
    
            DB::commit();
    
            return redirect('/configuration/supplier/')->with('success', 'Supplier berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $supplierId)
    {
        $req->validate([
            'name'    => 'nullable|string|min:3|max:50',
            'email'   => 'nullable|string|min:3|max:50|unique:suppliers,email',
            'phone'   => 'nullable|integer',
            'address' => 'nullable|string|min:3|max:255',
        ]);
        DB::beginTransaction(); 
        try {
            $supplier          = Supplier::findOrFail($supplierId);
            $supplier->name    = $req->name;
            $supplier->email   = $req->email;
            $supplier->phone   = $req->phone;
            $supplier->address = $req->address;
    
            $supplier->save();
    
            DB::commit(); 
    
            return redirect('/configuration/supplier/')->with('success', 'Data Supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            $supplier->status = Supplier::STATUS_INACTIVE;
            $supplier->save();
            $message = 'Data Supplier Berhasil Dihapus';
            return redirect('/configuration/supplier/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/configuration/supplier/')
                ->with('error', 'Supplier tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

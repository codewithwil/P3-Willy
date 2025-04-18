<?php

namespace App\Http\Controllers\API\Resources\Service;

use App\Http\Controllers\Controller;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Company\Company;
use App\Models\Resources\Service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceC extends Controller
{

    public function index()
    {
        $user = Auth::user();
    
        if ($user->hasRole('admin') && $user->branch_id === null) {
            $service = Service::where('status', Service::STATUS_ACTIVE)
                ->with('branch')
                ->get();
        } else {
            $service = Service::where('branch_id', Auth::user()->branch_id)->get();
        }
    
        return view('admin.resources.service.index', compact('service'));
    }

    public function invoice(){
        $service = Service::where('status', Service::STATUS_ACTIVE)->get();
        $company  = Company::first();
        return view('admin.resources.service.invoice', compact('service', 'company'));
    }

    public function create(){
        $branch = Branch::all();
        return view('admin.resources.service.create', compact('branch'));
    }

    public function edit($serviceId){
        $branch = Branch::all();
        $service = Service::findOrFail($serviceId);
        return view('admin.resources.service.update', compact('service', 'branch'));
    }

    public function store(Request $req){
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($req->all(), [
                'branch_id'     => 'required',
                'name'          => 'required|string|min:3|max:75',
                'pricePerUnit'  => 'required',
                'unitType'      => 'required',
                'minQuantity'   => 'required',
                'description'   => 'required|string|min:3|max:255',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            Service::create([
                'branch_id'     => $req->input('branch_id'),
                'name'          => $req->input('name'),
                'pricePerUnit'  => $req->input('pricePerUnit'),
                'unitType'      => $req->input('unitType'),
                'minQuantity'   => $req->input('minQuantity'),
                'description'   => $req->input('description'),
            ]);
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Layanan berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $req, $serviceId)
    {
        $req->validate([
            'branch_id'     => 'nullable',
            'name'          => 'nullable|string|min:3|max:75',
            'pricePerUnit'  => 'nullable',
            'unitType'      => 'nullable',
            'minQuantity'   => 'nullable',
            'description'   => 'nullable|string|min:3|max:255',
        ]);
        DB::beginTransaction(); 
        try {
            $service               = Service::findOrFail($serviceId);
            $service->branch_id    = $req->branch_id;
            $service->name         = $req->name;
            $service->pricePerUnit = $req->pricePerUnit;
            $service->unitType     = $req->unitType;
            $service->minQuantity  = $req->minQuantity;
            $service->description  = $req->description;
    
            $service->save();
    
            DB::commit(); 
    
            return redirect('/setting/service/')->with('success', 'Data Layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($serviceId)
    {
        try {
            $service = Service::findOrFail($serviceId);
            $service->status = Service::STATUS_NOTACTIVE;
            $service->save();
            $message = 'Data Layanan Berhasil Dihapus';
            return redirect('/setting/service/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/setting/service/')
                ->with('error', 'Layanan tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

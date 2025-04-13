<?php

namespace App\Http\Controllers\API\Resources\TypeVehicle;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Resources\TypeVehicle\TypeVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeVehicleC extends Controller
{
    public function index(){
        $typeVehicle = TypeVehicle::where('status', TypeVehicle::STATUS_ACTIVE)->get();
        return view('admin.resources.typeVehicle.index', compact('typeVehicle'));
    }

    public function invoice(){
        $typeVehicle = TypeVehicle::where('status', TypeVehicle::STATUS_ACTIVE)->get();
        $company  = Company::first();
        return view('admin.resources.typeVehicle.invoice', compact('typeVehicle', 'company'));
    }

    public function create(){
        return view('admin.resources.typeVehicle.create');
    }

    public function edit($TypeVId){
        $typeVehicle = TypeVehicle::findOrFail($TypeVId);
        return view('admin.resources.typeVehicle.update', compact('typeVehicle'));
    }

    public function store(Request $req){
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($req->all(), [
                'type'  => 'required|integer',
                'name'  => 'required|string|min:3|max:50',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator)->withInput();
            }
            $typeVehicle = TypeVehicle::create([
                'type'  => $req->input('type'),
                'name'  => $req->input('name'),
            ]);;
    
            DB::commit();
    
            return redirect('/configuration/typeVehicle/')->with('success', 'Tipe Kendaraan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $TypeVId)
    {
        $req->validate([
            'type'  => 'required|integer',
            'name'  => 'required|string|min:3|max:50',
        ]);
        DB::beginTransaction(); 
        try {
            $typeVehicle       = TypeVehicle::findOrFail($TypeVId);
            $typeVehicle->name = $req->name;
            $typeVehicle->type = $req->type;
    
            $typeVehicle->save();
    
            DB::commit(); 
    
            return redirect('/configuration/typeVehicle/')->with('success', 'Data Tipe Kendaraan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($TypeVId)
    {
        try {
            $typeVehicle = TypeVehicle::findOrFail($TypeVId);
            $typeVehicle->status = TypeVehicle::STATUS_INACTIVE;
            $typeVehicle->save();
            $message = 'Data Tipe Kendaraan Berhasil Dihapus';
            return redirect('/configuration/typeVehicle/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/configuration/typeVehicle/')
                ->with('error', 'Tipe Kendaraan tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

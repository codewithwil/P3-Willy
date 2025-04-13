<?php

namespace App\Http\Controllers\API\Resources\Building;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Building\Building
};
use App\Models\Resources\Company\Company;
use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator,
};

class BuildingC extends Controller
{
    public function index()
    {
        $building = Building::where('status', Building::STATUS_ACTIVE)->get();
        return view('admin.resources.building.index', compact('building'));
    }
    
    public function invoice(){
        $building = Building::where('status', Building::STATUS_ACTIVE)->get();
        $company  = Company::first();
        return view('admin.resources.building.invoice', compact('building', 'company'));
    }

    public function create(){
        return view('admin.resources.building.create');
    }

    public function edit($buldingId){
        $building = Building::findOrFail($buldingId);
        return view('admin.resources.building.update', compact('building'));
    }

    public function store(Request $req){
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'buildingName' => 'required|string|min:3|max:50',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $unit = Building::create([
                'buildingName' => $req->input('buildingName'),
            ]);
    
            DB::commit();
    
            return redirect('/configuration/building/')->with('success', 'Gedung berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $buildingId){
        $req->validate([
            'buildingName' => 'nullable|string|min:3|max:50',
        ]);
        DB::beginTransaction();
        try {
            $build               = Building::findOrFail($buildingId);
            $build->buildingName = $req->buildingName;

            $build->save();

            DB::commit();

            return redirect('/configuration/building/')->with('success', 'Data Gedung berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($buildingId){
        DB::beginTransaction();  
    
        try {
            $build         = Building::findOrFail($buildingId);
            $build->status = Building::STATUS_INACTIVE;
            $build->save();
            DB::commit();  
    
            $message = 'Data Gedung Berhasil Dihapus';  
            return redirect('/configuration/building/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/configuration/building/')
                ->with('error', 'Gedung tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\API\Resources\Unit;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Unit\Unit
};
use App\Models\Resources\Company\Company;
use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator,
};

class UnitC extends Controller
{
    public function index()
    {
        $unit = Unit::where('status', Unit::STATUS_ACTIVE)->get();
        return view('admin.resources.unit.index', compact('unit'));
    }

    public function invoice(){
        $unit    = Unit::where('status', Unit::STATUS_ACTIVE)->get();
        $company = Company::first();
        return view('admin.resources.unit.invoice', compact('unit', 'company'));
    }
    public function create()
    {
        return view('admin.resources.unit.create');
    }

    public function edit($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        return view('admin.resources.unit.update', compact('unit'));
    }


    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'name'          => 'required|string|min:3|max:50',
                'abbreviation'  => 'nullable|string|min:1|max:50',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $unit = Unit::create([
                'name'         => $req->input('name'),
                'abbreviation' => $req->input('abbreviation'),
            ]);
    
            DB::commit();
    
            return redirect('/configuration/unit/')->with('success', 'Satuan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function update(Request $req, $unitId)
    {
        $req->validate([
            'name'          => 'nullable|string|min:3|max:50',
            'abbreviation'  => 'nullable|string|min:1|max:50',
        ]);
        DB::beginTransaction();
        try {
            $unit               = Unit::findOrFail($unitId);
            $unit->name         = $req->name;
            $unit->abbreviation = $req->abbreviation;

            $unit->save();

            DB::commit();

            return redirect('/configuration/unit/')->with('success', 'Data Satuan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($unitId) {
        DB::beginTransaction();  
    
        try {
            $unit         = Unit::findOrFail($unitId);
            $unit->status = Unit::STATUS_INACTIVE;
            $unit->save();
            DB::commit();  
    
            $message = 'Data Satuan Berhasil Dihapus';  
            return redirect('/configuration/unit/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/configuration/unit/')
                ->with('error', 'Satuan tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}

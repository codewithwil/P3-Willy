<?php

namespace App\Http\Controllers\API\Resources\ManagementShift\Shift;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Resources\ManagementShift\Shift
};

use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\DB,
    Support\Facades\Validator
};


class ShiftC extends Controller
{

    public function index(){
        $shift = Shift::where('status', 1)->get();
        return view('admin.resources.shift.index', compact('shift'));
    }


    public function create(){
        return view('admin.resources.shift.create');
    }

    public function invoice(){
        $shift = Shift::where('status', 1)->get();
        $company = Company::first();
        return view('admin.resources.shift.invoice', compact('shift', 'company'));
    }

    public function edit($shiftId){
        $shift = Shift::findOrFail($shiftId);
        return view('admin.resources.shift.update', compact('shift'));
    }

    public function store(Request $req)
    {
        // dd($req->all());
        DB::beginTransaction();
        try {
            $useName = Auth::user()->name;
            $validator = Validator::make($req->all(), [
                'shiftName'  => 'required|string|min:3|max:50',
                'start_time' => 'required|date_format:H:i',
                'end_time'   => 'required|date_format:H:i',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }

    
            $shift = Shift::create([
                'shiftName'  => $req->input('shiftName'),
                'start_time' => $req->input('start_time'),
                'end_time'   => $req->input('end_time'),
                'createdBy'  => $useName,
                'updatedBy'  => $useName,
            ]);
    
            DB::commit();
    
            return redirect('/resources/shift/')->with('success', 'Shift berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function update(Request $req, $shiftId)
    {
        // dd($req->all());
        $req->validate([
            'shiftName'  => 'nullable|string|min:3|max:50',
            'start_time' => 'nullable',
            'end_time'   => 'nullable',
        ]);
        DB::beginTransaction();
        $useName = Auth::user()->name;
        try {
            $shift             = Shift::findOrFail($shiftId);
            $shift->shiftName  = $req->shiftName;
            $shift->start_time = $req->start_time;
            $shift->end_time   = $req->end_time;
            $shift->updatedBy  = $useName; 

            $shift->save();

            DB::commit();

            return redirect('/resources/shift/')->with('success', 'Data Shift berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($shiftId) {
        DB::beginTransaction();  
    
        try {
            $shift         = Shift::findOrFail($shiftId);
            $shift->status = Shift::STATUS_INACTIVE;
            $shift->save();
            DB::commit();  
    
            $message = 'Data Shift Berhasil Dihapus';  
            return redirect('/resources/shift/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/resources/shift/')
                ->with('error', 'Shift tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

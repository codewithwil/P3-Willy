<?php

namespace App\Http\Controllers\API\Resources\ManagementShift\EmployeeShift;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Resources\ManagementShift\EmployeeShift;
use App\Models\Resources\ManagementShift\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeShiftC extends Controller
{
    public function index(){
        $empShift = EmployeeShift::where('status', EmployeeShift::STATUS_ACTIVE)
                                    ->with(['users', 'shift'])
                                    ->get();
        return view('admin.resources.empShift.index', compact('empShift'));
    }

    public function invoice(){
        $empShift = EmployeeShift::where('status', EmployeeShift::STATUS_ACTIVE)
                                    ->with(['users', 'shift'])
                                    ->get();
        $company = Company::first();
        return view('admin.resources.empShift.invoice', compact('empShift', 'company'));
    }

    public function create(){
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'pengguna');
        })->get();
        
        $shift = Shift::where('status', Shift::STATUS_ACTIVE)->get();
        return view('admin.resources.empShift.create', compact('users', 'shift'));
    }

    public function edit($empShiftId){
        $empShift = EmployeeShift::findOrFail($empShiftId);
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'pengguna');
        })->get();
        $shift = Shift::where('status', Shift::STATUS_ACTIVE)->get();
        return view('admin.resources.empShift.update', compact('empShift', 'users', 'shift'));
    }

    public function store(Request $req){
        DB::beginTransaction();
        try {
            $useName   = Auth::user()->name;
            $validator = Validator::make($req->all(), [
                'user_id'    => 'required|integer',
                'shift_id'   => 'required|integer',
                'date'       => 'required|date',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }

    
            $empShift = EmployeeShift::create([
                'user_id'    => $req->input('user_id'),
                'shift_id'   => $req->input('shift_id'),
                'date'       => $req->input('date'),
                'createdBy'  => $useName,
                'updatedBy'  => $useName,
            ]);
    
            DB::commit();
    
            return redirect('/resources/empShift/')->with('success', 'Shift Pekerja berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $empShiftId){
        $req->validate([
            'user_id'    => 'nullable|integer',
            'shift_id'   => 'nullable|integer',
            'date'       => 'nullable|date',
        ]);
        DB::beginTransaction();
        $useName = Auth::user()->name;
        try {
            $shift            = EmployeeShift::findOrFail($empShiftId);
            $shift->user_id   = $req->user_id;
            $shift->shift_id  = $req->shift_id;
            $shift->date      = $req->date;
            $shift->updatedBy = $useName; 

            $shift->save();

            DB::commit();

            return redirect('/resources/empShift/')->with('success', 'Data Shift Pekerja berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($empShiftId) {
        DB::beginTransaction();  
    
        try {
            $shift         = EmployeeShift::findOrFail($empShiftId);
            $shift->status = EmployeeShift::STATUS_INACTIVE;
            $shift->save();
            DB::commit();  
    
            $message = 'Data Shift Pekerja Berhasil Dihapus';  
            return redirect('/resources/empShift/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/resources/empShift/')
                ->with('error', 'Shift Pekerja tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

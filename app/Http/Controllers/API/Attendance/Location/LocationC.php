<?php

namespace App\Http\Controllers\API\Attendance\Location;

use App\{
    Http\Controllers\Controller,
    Models\Attendance\Location\Location
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationC extends Controller
{
    public function index(){
        $location = Location::first();
        return view('admin.attendance.location.index', compact('location'));
    }

    public function create(){
        return view('admin.attendance.location.create');  
    }

    public function store(Request $req){
        // dd($req->all());
        $validatedData   = $req->validate([
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'radius'     => 'required|numeric',
            'start_time' => 'required|',
            'end_time'   => 'required|',
        ]);

        DB::beginTransaction();
        try {
            $location = Location::first();

            if (!$location) {
                $location = new Location($validatedData);
                $location->save();
            }else {
                $location->update($validatedData);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data lokasi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }
}

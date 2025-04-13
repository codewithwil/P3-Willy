<?php

namespace App\Http\Controllers\API\Resources\Building;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Building\Room,
    Models\Resources\Building\Building,
};
use App\Models\Resources\Company\Company;
use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator,
};

class RoomC extends Controller
{
    public function index()
    {
        $rooms = Room::where('roomStatus', Room::STATUS_ACTIVE)
                     ->with('building')
                     ->get();
                     
        return view('admin.resources.room.index', compact('rooms')); 
    }

    public function invoice(){
        $rooms = Room::where('roomStatus', Room::STATUS_ACTIVE)
        ->with('building')
        ->get();

        $company = Company::first();
        
        return view('admin.resources.room.invoice', compact('rooms', 'company')); 
    }

    public function create(){
        $building = Building::where('status', Building::STATUS_ACTIVE)->get();
        return view('admin.resources.room.create', compact('building'));
    }

    public function edit($roomId)
    {
        $rooms = Room::with('building')->findOrFail($roomId);
        $buildings = Building::where('status', Building::STATUS_ACTIVE)->get();
        return view('admin.resources.room.update', compact('rooms', 'buildings'));
    }
    

    public function store(Request $req){
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'building_Id' => 'required|integer',
                'roomName'    => 'required|string|min:3|max:50',
                'floor'       => 'required|integer|min:1',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $r = Room::create([
                'building_Id' => $req->input('building_Id'),
                'roomName'    => $req->input('roomName'),
                'floor'       => $req->input('floor'),
            ]);
    
            DB::commit();
    
            return redirect('/configuration/rooms/')->with('success', 'Ruangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $roomId){
        $req->validate([
            'building_Id' => 'nullable|integer',
            'roomName'    => 'nullable|string|min:3|max:50',
            'floor'       => 'nullable|integer|min:1',
        ]);
        DB::beginTransaction();
        try {
            $r              = Room::findOrFail($roomId);
            $r->building_Id = $req->building_Id;
            $r->roomName    = $req->roomName;
            $r->floor       = $req->floor;
            $r->save();
            DB::commit();

            return redirect('/configuration/rooms/')->with('success', 'Data Ruangan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($roomId){
        DB::beginTransaction();  
    
        try {
            $r         = Room::findOrFail($roomId);
            $r->roomStatus = Room::STATUS_INACTIVE;
            $r->save();
            DB::commit();  
    
            $message = 'Data Ruangan Berhasil Dihapus';  
            return redirect('/configuration/rooms/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/configuration/rooms/')
                ->with('error', 'Ruangan tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

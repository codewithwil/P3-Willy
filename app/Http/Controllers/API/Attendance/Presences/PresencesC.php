<?php

namespace App\Http\Controllers\API\Attendance\Presences;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Location\Location;
use App\Models\Attendance\Presences\Presences;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;

class PresencesC extends Controller
{
    public function index(){
        $presences = Presences::with('users')->get();
        return view('admin.attendance.presences.index', compact('presences'));
    }

    public function attendance(){
        $user_id = Auth::user()->id;
        $sudahAbsen = Presences::where('user_id', $user_id)
                           ->whereDate('date', Carbon::today())
                           ->exists();
        return view('admin.attendance.presences.attendance', compact('sudahAbsen'));
    }

    public function edit($presenceId){
        $presences = Presences::findOrFail($presenceId);
        return view('admin.attendance.presences.edit', compact('presences'));
    }

    
    public function store(Request $req)
    {
        Log::info('Mulai proses absensi', ['request_data' => $req->all()]);
    
        // Validasi data
        $validatedData = $req->validate([
            'user_id'    => 'required|numeric',
            'date'       => 'required|date',
            'entry_time' => 'required',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
        ]);

        $validatedData['entry_time'] = Carbon::parse($req->input('entry_time'))->format('H:i:s');

        try {
            DB::beginTransaction();
    
            $user_id = Auth::user()->id;
            Log::info('User yang sedang absen', ['user_id' => $user_id]);
    
            $sudahAbsen = Presences::where('user_id', $user_id)
                            ->whereDate('date', Carbon::today())
                            ->exists();
            if ($sudahAbsen) {
                Log::warning('User sudah absen hari ini', ['user_id' => $user_id]);
                return redirect()->back()->with('error', 'Anda sudah absen hari ini.');
            }
    
            $location = Location::first();
            if (!$location) {
                Log::error('Lokasi belum diatur dalam database');
                return redirect()->back()->with('error', 'Lokasi belum diatur.');
            }
    
            Log::info('Data lokasi ditemukan', [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'radius' => $location->radius
            ]);
    
            $distance = $this->haversineDistance(
                $validatedData['latitude'],
                $validatedData['longitude'],
                $location->latitude,
                $location->longitude
            );
    
            Log::info('Jarak pengguna dari lokasi', ['distance' => $distance]);
    
            if ($distance > $location->radius) {
                Log::warning('User berada di luar area absen', [
                    'user_id' => $user_id,
                    'distance' => $distance,
                    'radius' => $location->radius
                ]);
                return redirect()->back()->with('error', 'Anda berada di luar area absen.');
            }
    
            $status_presence = $this->calculatePresenceStatus($validatedData['entry_time'], $location->start_time);
            Log::info('Status kehadiran ditentukan', ['status_presence' => $status_presence]);
    
            Presences::create([
                'user_id'         => $validatedData['user_id'],
                'date'            => $validatedData['date'],
                'entry_time'      => $validatedData['entry_time'],
                'latitude'        => $validatedData['latitude'],
                'longitude'       => $validatedData['longitude'],
                'status_presence' => $status_presence,
            ]);
    
            DB::commit();
            Log::info('Absensi berhasil disimpan', ['user_id' => $validatedData['user_id']]);
    
            return redirect()->back()->with('success', 'Absensi berhasil!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Terjadi kesalahan saat proses absensi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function update(Request $req, $presenceId){

    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; 

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; 
    }

    private function calculatePresenceStatus($entryTime, $startTime)
    {
        $entryTime  = Carbon::createFromFormat('H:i:s', $entryTime);
        $startTime  = Carbon::createFromFormat('H:i:s', $startTime);
    
        // Hitung selisih menit
        $diffInMinutes = $entryTime->diffInMinutes($startTime, false);
    
        if ($diffInMinutes <= 0) {
            return Presences::STATUS_HADIR; 
        } elseif ($diffInMinutes > 0 && $diffInMinutes <= 15) {
            return Presences::STATUS_TERLAMBAT; 
        } else {
            return Presences::STATUS_ABSEN; 
        }
    }
    


}

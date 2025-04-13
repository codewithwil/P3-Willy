<?php

namespace App\Console\Commands\Presences;

use App\Models\Attendance\Location\Location;
use App\Models\Attendance\Presences\Presences;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoAbsent extends Command
{
    protected $signature = 'absen:auto';
    protected $description = 'Menandai user sebagai absen jika tidak hadir sampai batas waktu tertentu';

    public function handle()
    {
        Log::info("AutoAbsent: Memulai proses auto absen...");

        $location = Location::first();
        
        if (!$location) {
            Log::error('AutoAbsent: Lokasi belum dikonfigurasi.');
            return;
        }

        Log::info("AutoAbsent: Lokasi ditemukan dengan end_time: " . $location->end_time);

        $batasWaktuAbsen = Carbon::createFromFormat('H:i:s', $location->end_time);
        Log::info("AutoAbsent: Batas waktu absen adalah " . $batasWaktuAbsen->format('H:i:s'));

        $usersBelumAbsen = User::whereDoesntHave('presences', function ($query) {
            $query->whereDate('date', Carbon::today());
        })->get();

        Log::info("AutoAbsent: Jumlah user yang belum absen: " . $usersBelumAbsen->count());

        foreach ($usersBelumAbsen as $user) {
            Log::info("AutoAbsent: Memeriksa user ID: " . $user->id . " (Nama: " . $user->name . ")");

            if (Carbon::now()->greaterThanOrEqualTo($batasWaktuAbsen)) {
                Log::info("AutoAbsent: User ID " . $user->id . " melewati batas waktu, memproses absen...");

                try {
                    $dataPresensi = [
                        'user_id'         => $user->id,
                        'date'            => now()->format('Y-m-d'),
                        'entry_time'      => null, 
                        'latitude'        => null, 
                        'longitude'       => null,
                        'status_presence' => Presences::STATUS_ABSEN, 
                    ];

                    Log::info("AutoAbsent: Data yang akan disimpan ke database:", $dataPresensi);

                    $presensi = Presences::create($dataPresensi);

                    Log::info("AutoAbsent: Berhasil menyimpan presensi untuk User ID: " . $user->id);
                } catch (\Exception $e) {
                    Log::error("AutoAbsent: Gagal menyimpan presensi untuk User ID: " . $user->id . " | Error: " . $e->getMessage());
                }
            } else {
                Log::info("AutoAbsent: User ID " . $user->id . " belum melewati batas waktu, tidak diproses.");
            }
        }

        Log::info('AutoAbsent: Proses selesai.');
    }
}

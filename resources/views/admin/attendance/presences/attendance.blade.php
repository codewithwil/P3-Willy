@extends('admin.template.template')
@section('title', 'Absensi Karyawan')

@section('content')
@push('css')
<style>
    .absensi-card {
        max-width: 800px;
        margin: auto;
        border-radius: 12px;
        overflow: hidden;
    }
    .btn-absen {
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        transition: 0.3s;
    }
    .btn-absen:hover {
        transform: scale(1.05);
    }
    .message-box {
        background-color: #e0f7fa;
        border-left: 5px solid #00796b;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
        text-align: center;
    }
</style>
@endpush

<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow absensi-card text-center">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Absensi Hari Ini</h4>
        </div>
        <div class="card-body p-4">
            @if($sudahAbsen)
                <div class="message-box">
                    <h5 class="text-success">✅ Anda sudah absen!</h5>
                    <p>Terima kasih sudah datang tepat waktu. Silakan kembali besok! 😊</p>
                </div>
                <button type="button" class="btn btn-secondary btn-lg w-100 mt-3" disabled>Sudah Absen</button>
            @else
                <form action="/attendance/presences/store" method="POST" onsubmit="return checkLocation()">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" id="entry_time" name="entry_time" required>
                    <input type="hidden" id="latitude" name="latitude" required>
                    <input type="hidden" id="longitude" name="longitude" required>

                    <p id="location-status" class="text-danger">Lokasi belum diperoleh</p>
                    <button type="button" class="btn btn-warning mt-3" id="btn-location">📍 Izinkan Lokasi</button>

                    <button type="submit" id="absen-button" class="btn btn-success btn-lg w-100 btn-absen mt-3" disabled>🕒 Absen Sekarang</button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("btn-location").addEventListener("click", requestLocation);
        let now = new Date();
        document.getElementById("entry_time").value = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
    });

    function requestLocation() {
        console.log("requestLocation dipanggil");
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log("Lokasi didapatkan:", position.coords.latitude, position.coords.longitude);
                    document.getElementById("latitude").value = position.coords.latitude;
                    document.getElementById("longitude").value = position.coords.longitude;
                    document.getElementById("location-status").innerText = "📍 Lokasi diperoleh ✅";
                    document.getElementById("absen-button").disabled = false;
                    alert("Lokasi berhasil didapatkan!");
                },
                function(error) {
                    console.log("Gagal mendapatkan lokasi:", error);
                    alert("Gagal mendapatkan lokasi: " + error.message);
                }
            );
        } else {
            console.log("Geolocation tidak didukung");
            alert("Geolocation tidak didukung di browser ini.");
        }
    }

    function checkLocation() {
        let latitude = document.getElementById("latitude").value;
        let longitude = document.getElementById("longitude").value;
        if (!latitude || !longitude) {
            alert("Harap izinkan lokasi terlebih dahulu sebelum absen!");
            return false;
        }
        return true;
    }
</script>
@endpush
@endsection

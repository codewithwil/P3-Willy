@extends('admin.template.template')
@section('title', 'Lokasi Absensi')

@section('content')
@push('css')
<!-- Remove Select2 CSS link -->
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Lokasi Absensi</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">Lokasi Absensi</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="/attendance/locations/store" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <!-- Data Perusahaan Section -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Lokasi Absensi</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nama Perusahaan and Email -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">latitude</label>
                                    <input type="text" name="latitude" class="form-control" id="latitude" value="{{ $location->latitude  ?? ''}}" placeholder="Masukkan latitude">
                                </div>
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">longitude</label>
                                    <input type="text" name="longitude" class="form-control" id="longitude" value="{{ $location->longitude ?? ''}}" placeholder="Masukkan longitude">
                                </div>
                                <div class="mb-3">
                                    <label for="radius" class="form-label">Radius</label>
                                    <input type="number" name="radius" class="form-control" id="radius" value="{{ $location->radius ?? 'radius tidak di atur'}}" placeholder="Masukkan Radius Lokasi">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Waktu Mulai Absensi</label>
                                    <input type="time" name="start_time" class="form-control" id="start_time" value="{{ $location->start_time ?? 'start_time tidak di atur'}}" placeholder="Masukkan Waktu mulai absensi">
                                </div>
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Waktu Berakhir Absensi</label>
                                    <input type="time" name="end_time" class="form-control" id="end_time" value="{{ $location->end_time ?? 'end_time tidak di atur'}}" placeholder="Masukkan longitude">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

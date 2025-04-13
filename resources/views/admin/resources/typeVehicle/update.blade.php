@extends('admin.template.template')
@section('title', 'edit data tipe kendaraan')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Tipe Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Tipe Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('configuration/typeVehicle/update/'.$typeVehicle->TypeVId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Tipe Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Jenis Kendaraan</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">--- Jenis Kendaraan ---</option>
                                        <option value="1" {{ old('type', $typeVehicle->type) == 1 ? 'selected' : '' }}>Motor</option>
                                        <option value="2" {{ old('type', $typeVehicle->type) == 2 ? 'selected' : '' }}>Mobil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tipe Kendaraan</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $typeVehicle->name }}" placeholder="Masukkan Tipe Kendaraan">
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
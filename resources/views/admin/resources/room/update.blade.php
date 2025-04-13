@extends('admin.template.template')
@section('title', 'edit data ruangan')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Ruangan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item">Manajemen Ruangan</li>
                    <li class="breadcrumb-item active" aria-current="page">Ruangan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('configuration/rooms/update/'.$rooms->roomId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Ruangan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="roomName" class="form-label">Nama Ruangan</label>
                                    <input type="text" name="roomName" class="form-control" id="roomName" value="{{ $rooms->roomName }}"  placeholder="Masukkan Nama Ruangan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="buildings" class="form-label">Gedung</label>
                                    <select name="building_Id" class="form-control" id="buildings">
                                        <option value="">--- Pilih Gedung ---</option>
                                        @foreach ($buildings as $b)
                                            <option value="{{ $b->buildingId }}" {{ $b->buildingId == $rooms->building_Id ? 'selected' : '' }}>
                                                {{ $b->buildingName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="floor" class="form-label">Lantai</label>
                                    <input type="number" name="floor" class="form-control" id="floor" value="{{ $rooms->floor }}"  placeholder="Masukkan Lantai">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
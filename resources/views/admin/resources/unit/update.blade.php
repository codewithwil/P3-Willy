@extends('admin.template.template')
@section('title', 'edit Data Satuan')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Satuan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Satuan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('configuration/unit/update/'.$unit->unitId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Satuan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nameUnit" class="form-label">Nama Satuan</label>
                                    <input type="text" name="name" class="form-control" id="nameUnit" value="{{ $unit->name }}"  placeholder="Masukkan nama satuan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="abbrevationUnit" class="form-label">Singkatan Satuan *(tidak wajib)</label>
                                    <input type="text" name="abbreviation" class="form-control" id="abbrevationUnit" value="{{ $unit->abbreviation }}"  placeholder="Masukkan singkatan satuan">
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
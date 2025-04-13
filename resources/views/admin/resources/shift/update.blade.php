@extends('admin.template.template')
@section('title', 'edit Data Satuan')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Satuan</h3></div>
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
            <form class="row g-3" action="{{ url('resources/shift/update/'.$shift->shiftId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Satuan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nameShift" class="form-label">Nama Shift</label>
                                    <input type="text" name="shiftName" class="form-control" id="nameShift"  value="{{ $shift->shiftName }}">
                                </div>
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Waktu Mulai Shift</label>
                                    <input type="time" name="start_time" class="form-control" id="start_time" value="{{ $shift->start_time }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Waktu Selesai Shift</label>
                                    <input type="time" name="end_time" class="form-control" id="end_time" value="{{ $shift->end_time }}">
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
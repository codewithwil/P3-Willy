@extends('admin.template.template')
@section('title', 'Edit Data Shift Pekerja')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Shift Pekerja</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Resources</li>
                    <li class="breadcrumb-item">Manajemen Shift Pekerja</li>
                    <li class="breadcrumb-item active" aria-current="page">Shift Pekerja</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('resources/empShift/update/'.$empShift->empShiftId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Shift Pekerja</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dateShift" class="form-label">Tanggal Shift</label>
                                    <input type="date" name="date" class="form-control" id="dateShift" value="{{ $empShift->date }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="users" class="form-label">Pekerja</label>
                                    <select name="user_id" class="form-control" id="users">
                                        <option value="">--- Pilih Pekerja ---</option>
                                        @foreach ($users as $us)  
                                            <option value="{{ $us->id }}" {{ $us->id == $empShift->user_id ? 'selected' : '' }}>
                                                {{ $us->name }} 
                                                @foreach ($us->getRoleNames() as $role)
                                                - {{ $role }}
                                                @endforeach
                                            </option>
                                        @endforeach
                                    </select>
                                
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shift" class="form-label">Shift</label>
                                    <select name="shift_id" id="shift" class="form-control">
                                        <option value="">--- Pilih Shift ---</option>
                                        @foreach ($shift as $s)
                                            <option value="{{ $s->shiftId }}" {{ $s->shiftId == $empShift->shift_id ? 'selected' : '' }}>
                                                {{ $s->shiftName }} 
                                            </option>                                            
                                        @endforeach
                                    </select>
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
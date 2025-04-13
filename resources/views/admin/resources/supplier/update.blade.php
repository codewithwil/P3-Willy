@extends('admin.template.template')
@section('title', 'Edit Data Supplier')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Supplier</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Supplier</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('configuration/supplier/update/'.$supplier->supplierId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Supplier</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nameSupp" class="form-label">Nama Supplier</label>
                                    <input type="text" name="name" class="form-control" id="nameSupp" value="{{ $supplier->name }}" placeholder="Masukkan nama supplier">
                                </div>
                                <div class="mb-3">
                                    <label for="emailSupp" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="emailSupp" value="{{ $supplier->email }}"  placeholder="Masukkan email supplier">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phoneSupp" class="form-label">Nomor Telepon</label>
                                    <input type="number" min="0" name="phone" class="form-control" id="phoneSupp" value="{{ $supplier->phone }}"  placeholder="Masukan Nomor Telepon Supplier">
                                </div>
                                <div class="mb-3">
                                    <label for="addressSupp" class="form-label">Alamat</label>
                                    <textarea name="address" id="addressSupp" cols="30" rows="4" class="form-control">{{ $supplier->address }}</textarea>
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
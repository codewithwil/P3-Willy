@extends('admin.template.template')
@section('title', 'edit akun user')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data User</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('people/users/update/'.$users->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
             
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Akun User</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emailUser" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="emailUser" value="{{ $users->email }}" placeholder="Masukkan Email">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    @if(auth()->user()->hasRole(['admin']))
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ $userRole === $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <select name="role" id="role" class="form-control" disabled>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ $userRole === $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                    </select>
                                    @endif
                                </div>
                                                           
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="passwordUser" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="passwordUser"  placeholder="Masukkan password">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                
                    <!-- Data User Section -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data User</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nama Data user Email -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nameUser" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="nameUser" value="{{ $users->name }}"  placeholder="Masukkan nama user">
                                </div>
                                <div class="mb-3">
                                    <label for="phoneUser" class="form-label">Nomor Telepon</label>
                                    <input type="number"  name="phone" class="form-control" id="phoneUser" value="{{ $users->phone }}" placeholder="Masukkan nomor telepon">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addressUser" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="address" id="addressUser" placeholder="Masukkan alamat perusahaan" cols="30" rows="4">{{ $users->address }}</textarea>
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
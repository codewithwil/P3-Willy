@extends('admin.template.template')
@section('title', 'edit akun user')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Owner</h3></div>
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
            <form class="row g-3" action="{{ url('people/owner/update/'.$users->ownerId) }}" method="POST" enctype="multipart/form-data">
                @csrf
             
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Akun Owner</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emailUser" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="emailUser" value="{{ $users->user->email }}" placeholder="Masukkan Email">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    @if(auth()->user()->hasRole(['admin']))
                                    <select name="role_display" id="role" class="form-control" disabled>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ $userRole === $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="role" value="{{ $userRole }}">
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
                                    <label for="foto" class="form-label">Foto</label>
                                    @if ($users->foto)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $users->foto) }}" 
                                                 alt="Foto Admin" 
                                                 class="rounded-circle" 
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file" name="foto" class="form-control" id="foto">
                                </div>                                  
                                <div class="mb-3">
                                    <label for="passwordUser" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="passwordUser"  placeholder="Masukkan password">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                
                    <!-- Data Supervisor Section -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Supervisor</h5>
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
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{$users->address}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="number"  name="telepon" class="form-control" id="telepon" value="{{ $users->telepon }}" placeholder="Masukkan nomor telepon">
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
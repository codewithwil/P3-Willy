@extends('admin.template.template')
@section('title', 'edit akun user')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Petugas</h3></div>
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
            <form class="row g-3" action="{{ url('people/employee/update/'.$users->employeeId) }}" method="POST" enctype="multipart/form-data">
                @csrf  
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Akun Petugas</h5>
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
                                           
                                <div class="mb-3">
                                    <label for="branch" class="form-label">Cabang</label>
                                    <select name="branch_id" class="form-control" id="branch">
                                        <option value="">--- Pilih Cabang ---</option>
                                        @if (Auth::user()->branch)
                                            <option value="{{ Auth::user()->branch->branchId }}" selected>
                                                {{ Auth::user()->branch->address }}
                                            </option>
                                        @else
                                            @foreach ($branch as $b)
                                                <option  value="{{ $b->branchId }}" {{ $b->branchId == $promo->branch_id ? 'selected' : '' }}>{{ $b->address }}</option>
                                            @endforeach
                                        @endif
                                        </select>
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
                
                    <!-- Data Petugas Section -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Petugas</h5>
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
                                <div class="mb-3">
                                    <label for="birthdate" class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birthdate" class="form-control" id="birthdate" value="{{ $users->birthdate }}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="number"  name="telepon" class="form-control" id="telepon" value="{{ $users->telepon }}" placeholder="Masukkan nomor telepon">
                                </div>
                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">Tanggal karyawan mulai bekerja</label>
                                    <input type="date" name="hire_date" class="form-control" id="hire_date" value="{{ $users->hire_date }}" >
                                </div>
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Gaji</label>
                                    <input type="number" name="salary" class="form-control" id="salary" value="{{ $users->salary }}" >
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="0" {{ old('gender', $users->gender) == 0 ? 'selected' : '' }}>Laki laki</option>
                                        <option value="1" {{ old('gender', $users->gender) == 1 ? 'selected' : '' }}>Perempuan</option>
                                    </select>
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
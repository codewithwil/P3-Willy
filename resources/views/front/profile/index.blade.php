@extends('front.layout.template') 
@section('title', 'Profil Saya')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold text-primary">Profil Saya</h2>
                <a href="{{ url('/order')}}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <p class="text-muted mb-4">Kelola informasi pribadi kamu untuk pengalaman lebih nyaman 👤</p>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ url('people/customer/profileUpdate/'.$users->customerId) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="text-center mb-4">
                            <img src="{{ $users->foto ? asset('storage/'.$users->foto) : asset('assets/img/default-avatar.png') }}" alt="Foto Profil" class="rounded-circle" width="100" height="100">
                            <div class="mt-2">
                                <label for="foto" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-camera me-1"></i> Ganti Foto
                                </label>
                                <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name', $users->name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email aktif" value="{{ old('email', $users->user->email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label fw-semibold">No Telepon</label>
                            <input type="tel" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('telepon', $users->telepon) }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ old('address', $users->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi Baru 
                                <small class="text-muted">(Kosongkan jika tidak ingin ganti)</small>
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

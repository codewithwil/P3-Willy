@extends('front.layout.template') 
@section('title', 'Daftar Member')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Gabung Jadi Member</h2>
                <p class="text-muted">Nikmati layanan eksklusif & diskon spesial hanya untuk member LaundryKu 💎</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <form action="{{ url('configuration/member/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="Id" value="{{ Auth::user()->id }}">

                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name', Auth::user()->customer->name ) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email aktif" value="{{ old('email', Auth::user()->email) }}"  required>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label fw-semibold">No Telepon</label>
                            <input type="number" min="0" name="telepon" class="form-control" placeholder="Masukkan telepon aktif" value="{{ old('name', Auth::user()->customer->telepon) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Biaya Pendaftaran</label>
                            <div class="bg-light p-3 rounded border d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-primary fs-5">Rp 50.000</span>
                                <small class="text-muted">* Pembayaran via e-wallet, VA, dll</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            <i class="bi bi-cash-coin me-2"></i> Daftar & Lanjut ke Pembayaran
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

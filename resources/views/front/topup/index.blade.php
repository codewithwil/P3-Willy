    @extends('front.layout.template') 
    @section('title', 'Top Up Saldo')

    @section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="fw-bold text-primary">Topup Saldo</h2>
                    <a href="{{ url('/order')}}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                    <p class="text-muted">Isi ulang saldo akun kamu dan lanjutkan pembayaran melalui Midtrans 💰</p>

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <form action="{{ url('front/profile/topupStore') }}" method="POST">
                            @csrf
                            <input type="hidden" name="Id" value="{{ Auth::user()->id }}">

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama</label>
                                <input type="text" class="form-control" value="{{ $users->name }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" value="{{ $users->user->email }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="form-label fw-semibold">Nominal Top Up</label>
                                <input type="number" name="amount" class="form-control" placeholder="Contoh: 50000" min="10000" required>
                                <small class="text-muted">* Minimal top up Rp 10.000</small>
                            </div>

                            <button type="submit" class="btn btn-success w-100 shadow-sm">
                                <i class="bi bi-wallet2 me-2"></i> Lanjutkan Pembayaran
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    @endsection

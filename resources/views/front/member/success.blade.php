@extends('front.layout.template')

@section('title', 'Pendaftaran Sukses')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Pendaftaran Member Berhasil!</h2>
                <p class="text-muted">Terima kasih telah bergabung. Pembayaran Anda berhasil diproses.</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <p>Selamat datang di LaundryKu! Anda sekarang menjadi member kami.</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/order') }}" class="btn btn-primary">Kembali ke Beranda</a>
            </div>

        </div>
    </div>
</div>
@endsection

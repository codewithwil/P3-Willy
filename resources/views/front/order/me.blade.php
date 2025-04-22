@extends('front.layout.template')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Pesanan Saya</h2>
                <p class="text-muted">Berikut daftar pesanan Anda</p>
            </div>

            @if($orders->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada pesanan.
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Opsi Pengantaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $order->service->name }}</td>
                                        <td>
                                            <span class="badge {{ $order->status == 0 ? 'bg-danger' : ($order->status == 1 ? 'bg-warning' : 'bg-success') }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>{{ $order->payment_label }}</td>
                                        <td>{{ $order->deliverOpLabel }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ url('/order') }}" class="btn btn-outline-primary">Kembali</a>
            </div>

        </div>
    </div>
</div>
@endsection

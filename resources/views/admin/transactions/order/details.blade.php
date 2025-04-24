@extends('admin.template.template')
@section('title', 'Detail Pemesanan Jasa Laundry')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Pemesanan Jasa Laundry: {{  \Carbon\Carbon::parse($order->created_at)->format('d/m/Y')  }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/order') }}">Pemesanan Jasa Laundry</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{  \Carbon\Carbon::parse($order->created_at)->format('d/m/Y')  }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header"><h3 class="card-title">Detail Barang Yang Dipesan oleh: {{ $order->customer->name }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                {{-- <th>Kode Pemesanan Jasa Laundry</th>
                                <td>{{ $order->itemCode ?? 'Kode Pemesanan Jasa Laundry'}}</td> --}}
                            </tr>
                            <tr>
                                <th>Tanggal Pemesanan</th>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Nama Pemesan</th>
                                <td>{{ $order->customer->name ?? 'Nama belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>cabang</th>
                                <td>{{ $order->branch->address ?? 'cabang belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Nama Jasa</th>
                                <td>{{ $order->service->name ?? 'layanan belum diasur' }}</td>
                            </tr>
                            <tr>
                                <th>Berat/Jumlah</th>
                                <td>{{ $order->weight ?? 'berat atau jumlah belum diatur' }} || {{ $order->service->unitType ?? 'satuan belum di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $order->note ?? 'Catatan belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>{{ $order->payment_label ?? 'metode pembayaran belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Metode Pengantaran</th>
                                <td>{{ $order->deliverOp_label ?? 'metode pengantaran belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Ongkos Kirim</th>
                                <td>{{ $order->postage ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td>{{ $order->total ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Status Pemesanan</th>
                                <td>{{ $order->status_label ?? 'status belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Informasi Tambahan</th>
                                <td>Dibuat Pada: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Diperbarui Pada: {{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y') }}</td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                <a href="{{url('/transactions/order')}}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
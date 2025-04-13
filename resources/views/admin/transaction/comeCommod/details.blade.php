@extends('admin.template.template')
@section('title', 'Detail Pembelian Barang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Pembelian Barang: {{ $comeCommod->comComeCode }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/comeCommod') }}">Pembelian Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $comeCommod->comComeCode }}</li>
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
                    <div class="card-header"><h3 class="card-title">Detail Pembelian Barang: {{ $comeCommod->comComeCode }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Pembelian Barang</th>
                                <td>{{ $comeCommod->comComeCode ?? 'Kode Barang Masuk'}}</td>
                            </tr>
                            <tr>
                                <th>Barang Yang Dibeli</th>
                                <td>
                                    @if($comeCommod->commodities->isNotEmpty())
                                        <ul class="list-unstyled d-flex flex-wrap gap-3">
                                            @foreach($comeCommod->commodities as $commodity)
                                                <li class="d-flex align-items-center border p-2 rounded" style="gap: 10px;">
                                                    <img src="{{ asset('storage/'. $commodity->image) }}" 
                                                        alt="{{ $commodity->name }}" 
                                                        style="width: 100px; height: 70px; object-fit: cover;"
                                                        class="rounded border border-secondary">
                                                    <div>
                                                        <strong>{{ $commodity->name }}</strong>  
                                                        <br>
                                                        <small class="text-muted">Jumlah: ({{ $commodity->pivot->quantity }})</small>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-danger">Commodity not set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Supplier Barang</th>
                                <td>{{ $comeCommod->supplier ? $comeCommod->supplier->name : 'tanggal belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pembelian Barang</th>
                                <td>{{ $comeCommod->date ?? 'tanggal belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Diajukan Oleh</th>
                                <td>{{ $comeCommod->users->name ?? 'User belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Tipe Pembayaran</th>
                                <td>{{ $comeCommod->paymentText ?? 'tipe belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td>{{ 'Rp ' . number_format($comeCommod->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $comeCommod->note ?? 'catatan belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Status Barang</th>
                                <td>{{ $comeCommod->statusText ?? 'status belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Informasi Tambahan</th>
                                <td>Dibuat Pada: {{ \Carbon\Carbon::parse($comeCommod->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Diperbarui Pada: {{ \Carbon\Carbon::parse($comeCommod->updated_at)->format('d/m/Y') }}</td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection

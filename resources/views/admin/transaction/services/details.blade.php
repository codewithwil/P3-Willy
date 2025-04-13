@extends('admin.template.template')
@section('title', 'Detail Servis Barang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Servis Barang: {{ $services->serviceCode }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/services') }}">Servis Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $services->serviceCode }}</li>
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
                    <div class="card-header"><h3 class="card-title">Detail Barang Yang dipinjam: {{ $services->serviceCode }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Service</th>
                                <td>{{ $services->serviceCode ?? 'Kode Service'}}</td>
                            </tr>
                            <tr>
                                <th>Barang Yang Dipinjam</th>
                                <td>
                                    @if($services->commodities->isNotEmpty())
                                    <ul class="list-unstyled d-flex flex-wrap gap-3">
                                        @foreach($services->commodities as $commodity)
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
                                <th>Tanggal Servis Barang</th>
                                <td>{{ $services->serviceDate ?? 'tanggal belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Servis Selesai</th>
                                <td>{{ $services->returnServiceDate ?? 'tanggal belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Diajukan Oleh</th>
                                <td>{{ $services->users->name ?? 'User belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Nama toko</th>
                                <td>{{ $services->serviceName }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon Toko</th>
                                <td>{{ $services->servicePhone }}</td>
                            </tr>
                            <tr>
                                <th>Alamat Toko</th>
                                <td>{{ $services->serviceAddress ?? 'alamat blom di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Catatan Servis Barang</th>
                                <td>{{ $services->note ?? 'catatan belum di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Status Barang</th>
                                <td>{{ $services->statusText ?? 'status belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Informasi Tambahan</th>
                                <td>Dibuat Pada: {{ \Carbon\Carbon::parse($services->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Diperbarui Pada: {{ \Carbon\Carbon::parse($services->updated_at)->format('d/m/Y') }}</td>
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

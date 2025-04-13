@extends('admin.template.template')
@section('title', 'Detail Servis Kendaraan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Servis Kendaraan: {{ $services->serviceCode }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/serviceV') }}">Servis Kendaraan</a></li>
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
                    <div class="card-header"><h3 class="card-title">Detail Servis Kendaraan: {{ $services->serviceCode }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Servis Kendaraan</th>
                                <td>{{ $services->servCode ?? 'Kode Service belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Plat Kendaraan</th>
                                <td>{{ $services->platNo ?? 'Plat belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>{{ $services->customers ?? 'Pelanggan belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon Pelanggan</th>
                                <td>{{ $services->phoneCustomers ?? 'Nomor Telepon Kendaraan belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Merk Motor</th>
                                <td>{{ $services->brandMoto? $services->brandMoto->name :'Pelanggan belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Teknisi</th>
                                <td>{{ $services->empShift->users? $services->empShift->users->name :'Teknisi belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Servis Kendaraan</th>
                                <td>{{ $services->dateService ?? 'tanggal belum belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Servis Kendaraan</th>
                                <td>{{ $services->endService ?? 'tanggal belum belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kendaraan</th>
                                <td>{{ $services->typeVText ?? 'jenis kendaraan belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Tipe Kendaraan</th>
                                <td>{{ $services->typeVehicle->name ?? 'Tipe Kendaraan belum diatur'}}</td>
                            </tr>
                            <tr>
                                <th>Jenis Servis</th>
                                <td>{{ $services->typeText ?? 'jenis servis belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Sparepart yang Dipakai</th>
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
                                <th>Biaya Jasa Servis</th>
                                <td>{{ $services->ServPrice ?? 'biaya jasa servis belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Diskon Servis</th>
                                <td>{{ $services->discount ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Total Biaya</th>
                                <td>{{ 'Rp ' . number_format($services->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status Servis</th>
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

@extends('admin.template.template')
@section('title', 'Detail Barang Keluar')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Servis Barang: {{ $outCommod->outCode }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/outCommod') }}">Servis Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $outCommod->outCode }}</li>
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
                    <div class="card-header"><h3 class="card-title">Detail Barang Yang dipinjam: {{ $outCommod->outCode }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Service</th>
                                <td>{{ $outCommod->outCode ?? 'Kode Barang Keluar'}}</td>
                            </tr>
                            <tr>
                                <th>Barang Yang Dipinjam</th>
                                <td>
                                    @if($outCommod->commodities->isNotEmpty())
                                        @foreach($outCommod->commodities as $commodity)
                                            <img src="{{ asset('storage/'. $commodity->image) }}" alt="{{ $commodity->name }}" 
                                                style="max-width: 300px" 
                                                class="object-cover rounded border border-gray-300">
                                            {{ $commodity->name }} 
                                            | Jumlah: ({{ $commodity->pivot->quantity }})<br>
                                        @endforeach
                                    @else
                                        Commodity not set
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Barang keluar</th>
                                <td>{{ $outCommod->outDate ?? 'tanggal belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Diajukan Oleh</th>
                                <td>{{ $outCommod->users->name ?? 'User belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Detail Barang Keluar</th>
                                <td>
                                    @php
                                    $labels = [
                                        'serviceName'    => 'Nama Toko Servis',
                                        'customerName'   => 'Nama Pelanggan',
                                        'servicePhone'   => 'Nomor Telepon Toko Servis',
                                        'customerPhone'  => 'Nomor Telepon Pelanggan',
                                        'paymentMethod'  => 'Metode Pembayaran',
                                        'serviceAddress' => 'Alamat Toko Servis'
                                    ];
                                @endphp
                                
                                @if(!empty($outCommod->outDetails))
                                    @foreach($outCommod->outDetails as $key => $value)
                                        @if(!is_null($value) && $value !== '')  
                                            <p><strong>{{ $labels[$key] ?? ucfirst($key) }}:</strong> {{ $value }}</p>
                                        @endif
                                    @endforeach
                                @endif                
                                </td>  
                            </tr>
                            <tr>
                                <th>Catatan Barang Keluar</th>
                                <td>{{ $outCommod->note ?? 'catatan belum di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Status Barang</th>
                                <td>{{ $outCommod->statusText ?? 'status belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Informasi Tambahan</th>
                                <td>Dibuat Pada: {{ \Carbon\Carbon::parse($outCommod->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Diperbarui Pada: {{ \Carbon\Carbon::parse($outCommod->updated_at)->format('d/m/Y') }}</td>
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

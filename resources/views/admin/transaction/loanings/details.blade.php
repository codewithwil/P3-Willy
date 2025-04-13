@extends('admin.template.template')
@section('title', 'Detail Peminjaman')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Peminjaman: {{ $loanings->itemCode }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/loanings') }}">Peminjaman</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $loanings->itemCode }}</li>
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
                    <div class="card-header"><h3 class="card-title">Detail Barang Yang dipinjam: {{ $loanings->itemCode }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Peminjaman</th>
                                <td>{{ $loanings->itemCode ?? 'Kode Peminjaman'}}</td>
                            </tr>
                            <tr>
                                <th>Barang Yang Dipinjam</th>
                                <td>
                                    @if($loanings->commodities->isNotEmpty())
                                    <ul class="list-unstyled d-flex flex-wrap gap-3">
                                        @foreach($loanings->commodities as $commodity)
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
                                <th>Tanggal Peminjaman</th>
                                <td>{{ $loanings->note ?? 'catatan belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Dipinjam Oleh</th>
                                <td>{{ $loanings->users->name ?? 'User belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Catatan Peminjaman</th>
                                <td>{{ \Carbon\Carbon::parse($loanings->loanDate)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengembalian</th>
                                <td>{{ \Carbon\Carbon::parse($loanings->returnDate)->format('d/m/Y')}}</td>
                            </tr>
                            <tr>
                                <th>Status Barang</th>
                                <td>{{ $loanings->statusText ?? 'status belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Informasi Tambahan</th>
                                <td>Dibuat Pada: {{ \Carbon\Carbon::parse($loanings->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Diperbarui Pada: {{ \Carbon\Carbon::parse($loanings->updated_at)->format('d/m/Y') }}</td>
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

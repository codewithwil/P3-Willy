@extends('admin.template.template')
@section('title', 'Data Pengajuan Barang Kelar')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Pengajuan Barang Keluar</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Barang Keluar</li>
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
                    <div class="card-header"><h3 class="card-title">Pengajuan Barang Keluar</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/outCommodApps/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableOutCommod" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang Keluar</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Tanggal Barang Keluar</th>
                                        <th>Barang</th>
                                        <th>Jumlah Barang</th>
                                        <th>Tipe</th>
                                        <th>Catatan</th>
                                        <th>Status Barang Keluar</th>
                                        <th>Aksi</th>   
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outCommod as $out)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $out->outCode ?? 'kode barang keluar belum di atur' }}</td>
                                        <td>{{ $out->users ? $out->users->name : 'user belum di atur' }}</td>
                                        <td>{{ $out->dateOut }}</td>
                                        <td>
                                            @if($out->commodities->isNotEmpty())
                                                @foreach($out->commodities as $commodity)
                                                    {{ $commodity->name }}<br>
                                                @endforeach
                                            @else
                                                Commodity not set
                                            @endif
                                        </td>
                                        <td>
                                            @if($out->commodities->isNotEmpty())
                                                @foreach($out->commodities as $commodity)
                                                    {{ $commodity->pivot->quantity }}<br>
                                                @endforeach
                                            @endif
                                        </td>                                        
                                        <td>{{ $out->typeText ?? 'tipe belum di atur' }}</td>
                                        <td>{{ $out->note ?? 'catatan belum di atur' }}</td>
                                        <td>{{ $out->statusText }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                                                    @if($out->outStatus == \App\Models\Transactions\OutCommodity\OutCommodity::STATUS_DIAJUKAN)
                                                        <form action="{{ url('transactions/outCommodApps/update', $out->outComId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="outStatus" value="{{ \App\Models\Transactions\OutCommodity\OutCommodity::STATUS_DITOLAK }}">
                                                            <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan barang keluar ini?')">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ url('transactions/outCommodApps/update', $out->outComId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="outStatus" value="{{ \App\Models\Transactions\OutCommodity\OutCommodity::STATUS_KELUAR }}">
                                                            <button type="submit" class="btn btn-success text-light hover:text-green-400" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan barang keluar ini?')">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">
                                                            Pengajuan Barang Keluar sudah 
                                                            @if($out->outStatus == \App\Models\Transactions\OutCommodity\OutCommodity::STATUS_DITOLAK)
                                                                <span class="text-danger">ditolak</span>
                                                            @elseif($out->outStatus == \App\Models\Transactions\OutCommodity\OutCommodity::STATUS_KELUAR)
                                                                <span class="text-success">disetujui</span>
                                                            @else
                                                                <span class="text-secondary">Selesai</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>                                        
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
<script>
    new DataTable('#dataTableOutCommod');
</script>
@endpush
@endsection

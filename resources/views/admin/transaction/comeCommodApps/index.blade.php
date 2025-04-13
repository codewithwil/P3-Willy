@extends('admin.template.template')
@section('title', 'Data Pengajuan Barang Masuk')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Pengajuan Barang Masuk</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Barang Masuk</li>
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
                    <div class="card-header"><h3 class="card-title">Pengajuan Barang Masuk</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/comeCommodApps/invoice') }}" class="btn btn-warning ms-3 mt-3">
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
                                        <th>Kode Barang Masuk</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Barang</th>
                                        <th>Jumlah Barang</th>
                                        <th>Tanggal Barang Masuk</th>
                                        <th>Supplier</th>
                                        <th>Status Barang Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comeCommod as $come)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $come->comComeCode ?? 'Kode belum di atur' }}</td>
                                        <td>{{ $come->users ? $come->users->name : 'Nama belum diatur' }}</td>
                                        <td>
                                            @if($come->commodities->isNotEmpty())
                                                @foreach($come->commodities as $commodity)
                                                    {{ $commodity->name }}<br>
                                                @endforeach
                                            @else
                                                Commodity not set
                                            @endif
                                        </td>
                                        <td>
                                            @if($come->commodities->isNotEmpty())
                                                @foreach($come->commodities as $commodity)
                                                    {{ $commodity->pivot->quantity }}<br>
                                                @endforeach
                                            @endif
                                        </td>                                        
                                        <td>{{ $come->date ?? 'tanggal belum diatur' }}</td>
                                        <td>{{ $come->supplier ? $come->supplier->name : 'supplier belum diatur' }}</td>
                                        <td>{{ $come->statusText }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                                    @if($come->statusComeCom == \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_DIAJUKAN)
                                                        <form action="{{ url('transactions/comeCommodApps/update', $come->comeComdId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="statusComeCom" value="{{ \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_DITOLAK }}">
                                                            <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan barang masuk ini?')">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ url('transactions/comeCommodApps/update', $come->comeComdId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="statusComeCom" value="{{ \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_SUKSES }}">
                                                            <button type="submit" class="btn btn-success text-light hover:text-green-400" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan barang masuk ini?')">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">
                                                            Pengajuan Barang Masuk sudah 
                                                            @if($come->statusComeCom == \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_DITOLAK)
                                                                <span class="text-danger">ditolak</span>
                                                            @elseif($come->statusComeCom == \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_SUKSES)
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

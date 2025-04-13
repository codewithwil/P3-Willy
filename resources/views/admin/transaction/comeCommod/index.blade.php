@extends('admin.template.template')
@section('title', 'Pembelian Barang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Pembelian Barang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Pembelian Barang</li>
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
                    <div class="card-header"><h3 class="card-title">Pembelian Barang</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/comeCommod/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        @endif
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/comeCommod/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableLoanings" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pembelian Barang</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Barang</th>
                                        <th>Jumlah Barang</th>
                                        <th>Tanggal Pembelian Barang</th>
                                        <th>Supplier</th>
                                        <th>Status Pembelian Barang</th>
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
                                                <a href="{{ url('/transactions/comeCommod/details/' . $come->comeComdId) }}" class="btn btn-primary">
                                                    Detail
                                                </a>     
                                                @if($come->statusComeCom == \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_DIAJUKAN)
                                                    @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))                             
                                                        <a href="{{ url('/transactions/comeCommod/edit/' . $come->comeComdId) }}" class="btn btn-primary">
                                                            Edit
                                                        </a>  
                                                    @endif
                                                    @if(auth()->user()->hasRole(['admin']))                                   
                                                        <form action="{{ url('transactions/comeCommod/delete', $come->comeComdId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('POST') 
                                                            <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Are you sure?')">
                                                                Hapus   
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    Barang Masuk 
                                                    @if($come->statusComeCom == \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_DITOLAK)
                                                        <span class="text-danger">ditolak</span>
                                                    @elseif($come->statusComeCom == \App\Models\Transactions\ComeCommodity\ComeCommodity::STATUS_SUKSES)                                             
                                                        <span class="text-primary">Sukses</span>
                                                    @else
                                                        <span class="text-secondary">diproses lebih lanjut</span>
                                                    @endif
                                                </span>
                                            @endif
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
    new DataTable('#dataTableLoanings');
</script>
@endpush
@endsection

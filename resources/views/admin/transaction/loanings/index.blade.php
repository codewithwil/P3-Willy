@extends('admin.template.template')
@section('title', 'Peminjaman')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Peminjaman</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Peminjaman</li>
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
                    <div class="card-header"><h3 class="card-title">Peminjaman</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas', 'user']))
                        <a href="{{ url('transactions/loanings/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        @endif
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/loanings/invoice') }}" class="btn btn-warning ms-3 mt-3">
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
                                        <th>Kode Peminjaman</th>
                                        <th>Nama Peminjam</th>
                                        <th>Barang</th>
                                        <th>Jumlah Barang</th>
                                        <th>Tanggal Peminjaman</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>Status Peminjaman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loanings as $loan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $loan->itemCode ?? 'code not set' }}</td>
                                        <td>{{ $loan->users ? $loan->users->name : 'name not set' }}</td>
                                        <td>
                                            @if($loan->commodities->isNotEmpty())
                                                @foreach($loan->commodities as $commodity)
                                                    {{ $commodity->name }}<br>
                                                @endforeach
                                            @else
                                                Commodity not set
                                            @endif
                                        </td>
                                        <td>
                                            @if($loan->commodities->isNotEmpty())
                                                @foreach($loan->commodities as $commodity)
                                                    {{ $commodity->pivot->quantity }}<br>
                                                @endforeach
                                            @endif
                                        </td>                                        
                                        <td>{{ $loan->loanDate ?? 'date not set' }}</td>
                                        <td>{{ $loan->returnDate ?? 'tanggal belum di atur' }}</td>
                                        <td>{{ $loan->statusText }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ url('/transactions/loanings/details/' . $loan->loaningId) }}" class="btn btn-primary">
                                                    Detail
                                                </a>     
                                                @if($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DIAJUKAN)
                                                    @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas', 'user']))                             
                                                        <a href="{{ url('/transactions/loanings/edit/' . $loan->loaningId) }}" class="btn btn-primary">
                                                            Edit
                                                        </a>  
                                                    @endif
                                                    @if(auth()->user()->hasRole(['admin']))                                   
                                                        <form action="{{ url('transactions/loanings/delete', $loan->loaningId) }}" method="POST" style="display: inline;">
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
                                                    Barang Di pinjam 
                                                    @if($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DITOLAK)
                                                        <span class="text-danger">ditolak</span>
                                                    @elseif($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DIPINJAM)
                                                    @if(Auth::id() == $loan->user_id) 
                                                        <span class="text-success">disetujui</span>
                                                        <form action="{{ url('transactions/loanings/return/'. $loan->loaningId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                        
                                                            @if($loan->commodities->isNotEmpty())
                                                                @foreach($loan->commodities as $commodity)
                                                                    <input type="hidden" name="commodity_id[]" value='{{$commodity->commoditiesId}}'>
                                                                    <input type="hidden" name="quantity[]" value='{{ $commodity->pivot->quantity }}'>
                                                                @endforeach
                                                            @endif
                                                        
                                                            <button type="submit" class="btn btn-warning text-light hover:text-yellow-700" onclick="return confirm('Apakah Anda yakin ingin mengembalikan pinjaman ini?')">
                                                                Kembalikan
                                                            </button>
                                                        </form>        
                                                        @endif                                                
                                                    @elseif($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DIKEMBALIKAN)
                                                        <span class="text-primary">dikembalikan</span>
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

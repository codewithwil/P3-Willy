@extends('admin.template.template')
@section('title', 'Data Pengajuan Peminjaman')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Pengajuan Peminjaman</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item">Pengajuan</li>
                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Peminjaman</li>
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
                    <div class="card-header"><h3 class="card-title">Pengajuan Peminjaman</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/loaningsApps/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableLoaningsApp" class="table table-striped" style="width:100%">
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
                                                @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                                                    @if($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DIAJUKAN)
                                                        <form action="{{ url('transactions/loaningsApps/update', $loan->loaningId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="statusLoan" value="{{ \App\Models\Transactions\Loaning\Loaning::STATUS_DITOLAK }}">
                                                            <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ url('transactions/loaningsApps/update', $loan->loaningId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="statusLoan" value="{{ \App\Models\Transactions\Loaning\Loaning::STATUS_DIPINJAM }}">
                                                            <button type="submit" class="btn btn-success text-light hover:text-green-400" onclick="return confirm('Apakah Anda yakin ingin meminjamkan barang ini?')">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">
                                                            Peminjaman Pengajuan sudah 
                                                            @if($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DITOLAK)
                                                                <span class="text-danger">ditolak</span>
                                                            @elseif($loan->statusLoan == \App\Models\Transactions\Loaning\Loaning::STATUS_DIPINJAM)
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
    new DataTable('#dataTableLoaningsApp');
</script>
@endpush
@endsection

@extends('admin.template.template')
@section('title', 'Barang Keluar')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Barang Keluar</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Barang Keluar</li>
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
                    <div class="card-header"><h3 class="card-title">Barang Keluar</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/outCommod/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('transactions/outCommod/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableServices" class="table table-striped" style="width:100%">
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
                                                <a href="{{ url('/transactions/outCommod/details/' . $out->outComId) }}" class="btn btn-primary">
                                                    Detail
                                                </a>     
                                                @if($out->outStatus == \App\Models\Transactions\OutCommodity\OutCommodity::STATUS_DIAJUKAN)
                                                    @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))                             
                                                        <a href="{{ url('/transactions/outCommod/edit/' . $out->outComId) }}" class="btn btn-primary">
                                                            Edit
                                                        </a>  
                                                    @endif
                                                    @if(auth()->user()->hasRole(['admin']))                                   
                                                        <form action="{{ url('transactions/outCommod/delete', $out->outComId) }}" method="POST" style="display: inline;">
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
                                                    Servis Barang 
                                                    @if($out->outStatus == \App\Models\Transactions\Service\Service::STATUS_DITOLAK)
                                                        <span class="text-danger">ditolak</span>
                                                    @elseif($out->outStatus == \App\Models\Transactions\Service\Service::STATUS_DISERVIS)
                                                    @if(Auth::id() == $out->user_id) 
                                                        <span class="text-success">disetujui</span>
                                                        <form action="{{ url('transactions/outCommod/return/'. $out->outComId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                        
                                                            @if($out->commodities->isNotEmpty())
                                                                @foreach($out->commodities as $commodity)
                                                                    <input type="hidden" name="commodity_id[]" value='{{$commodity->commoditiesId}}'>
                                                                    <input type="hidden" name="quantity[]" value='{{ $commodity->pivot->quantity }}'>
                                                                @endforeach
                                                            @endif
                                                        
                                                            <button type="submit" class="btn btn-warning text-light hover:text-yellow-700" onclick="return confirm('Apakah Anda yakin Barang sudah di servis ini?')">
                                                                Selesai
                                                            </button>
                                                        </form>        
                                                        @endif                                                
                                                    @elseif($out->outStatus == \App\Models\Transactions\Service\Service::STATUS_SELESAI)
                                                        <span class="text-primary">Telah Selesai</span>
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
    new DataTable('#dataTableServices');
</script>
@endpush
@endsection

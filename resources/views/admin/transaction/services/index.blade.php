@extends('admin.template.template')
@section('title', 'Servis Barang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Servis Barang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Servis Barang</li>
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
                    <div class="card-header"><h3 class="card-title">Servis Barang</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/services/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('transactions/services/invoice') }}" class="btn btn-warning ms-3 mt-3">
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
                                        <th>Kode Servis</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Nama Toko Servis</th>
                                        <th>Barang</th>
                                        <th>Jumlah Barang</th>
                                        <th>Tanggal Servis</th>
                                        <th>Tanggal Selesai Servis</th>
                                        <th>Status Servis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $serv)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $serv->serviceCode ?? 'kode servis belum di atur' }}</td>
                                        <td>{{ $serv->users ? $serv->users->name : 'user belum di atur' }}</td>
                                        <td>{{ $serv->serviceName ?? 'nama toko belum di atur' }}</td>
                                        <td>
                                            @if($serv->commodities->isNotEmpty())
                                                @foreach($serv->commodities as $commodity)
                                                    {{ $commodity->name }}<br>
                                                @endforeach
                                            @else
                                                Commodity not set
                                            @endif
                                        </td>
                                        <td>
                                            @if($serv->commodities->isNotEmpty())
                                                @foreach($serv->commodities as $commodity)
                                                    {{ $commodity->pivot->quantity }}<br>
                                                @endforeach
                                            @endif
                                        </td>                                        
                                        <td>{{ $serv->serviceDate ?? 'tanggal belum di atur' }}</td>
                                        <td>{{ $serv->returnServiceDate ?? 'tanggal belum di atur' }}</td>
                                        <td>{{ $serv->statusText }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ url('/transactions/services/details/' . $serv->serviceId) }}" class="btn btn-primary">
                                                    Detail
                                                </a>     
                                                @if($serv->serviceStatus == \App\Models\Transactions\Service\Service::STATUS_DIAJUKAN)
                                                    @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas', 'user']))                             
                                                        <a href="{{ url('/transactions/services/edit/' . $serv->serviceId) }}" class="btn btn-primary">
                                                            Edit
                                                        </a>  
                                                    @endif
                                                    @if(auth()->user()->hasRole(['admin']))                                   
                                                        <form action="{{ url('transactions/services/delete', $serv->serviceId) }}" method="POST" style="display: inline;">
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
                                                    @if($serv->serviceStatus == \App\Models\Transactions\Service\Service::STATUS_DITOLAK)
                                                        <span class="text-danger">ditolak</span>
                                                    @elseif($serv->serviceStatus == \App\Models\Transactions\Service\Service::STATUS_DISERVIS)
                                                    @if(Auth::id() == $serv->user_id) 
                                                        <span class="text-success">disetujui</span>
                                                        <form action="{{ url('transactions/services/return/'. $serv->serviceId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                        
                                                            @if($serv->commodities->isNotEmpty())
                                                                @foreach($serv->commodities as $commodity)
                                                                    <input type="hidden" name="commodity_id[]" value='{{$commodity->commoditiesId}}'>
                                                                    <input type="hidden" name="quantity[]" value='{{ $commodity->pivot->quantity }}'>
                                                                @endforeach
                                                            @endif
                                                        
                                                            <button type="submit" class="btn btn-warning text-light hover:text-yellow-700" onclick="return confirm('Apakah Anda yakin Barang sudah di servis ini?')">
                                                                Selesai
                                                            </button>
                                                        </form>        
                                                        @endif                                                
                                                    @elseif($serv->serviceStatus == \App\Models\Transactions\Service\Service::STATUS_SELESAI)
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

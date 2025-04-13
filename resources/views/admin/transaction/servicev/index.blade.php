@extends('admin.template.template')
@section('title', 'Servis Kendaraan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Servis Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Servis Kendaraan</li>
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
                    <div class="card-header"><h3 class="card-title">Servis Kendaraan</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/serviceV/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('transactions/serviceV/invoice') }}" class="btn btn-warning ms-3 mt-3">
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
                                        <th>Merk Kendaraan</th>
                                        <th>Teknisi</th>
                                        <th>Tanggal Servis</th>
                                        <th>Tanggal Selesai Servis</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Status Servis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $serv)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $serv->servCode ?? 'kode servis belum di atur' }}</td>
                                        <td>{{ $serv->brandMoto ? $serv->brandMoto->name : 'merk belum di atur' }}</td>
                                        <td>{{ $serv->empShift->users ? $serv->empShift->users->name : 'teknisi belum di atur' }}</td>
                                        <td>{{ $serv->dateService ?? 'tanggal belum di atur' }}</td>                                  
                                        <td>{{ $serv->endService ?? 'tanggal belum di atur' }}</td>
                                        <td>{{ $serv->customers ?? 'tanggal belum di atur' }}</td>
                                        <td>{{ 'Rp ' . number_format($serv->total, 0, ',', '.') }}</td>
                                        <td>{{ $serv->statusText }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ url('/transactions/serviceV/details/' . $serv->serVId) }}" class="btn btn-primary">
                                                    Detail
                                                </a>     
                                                    @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))                             
                                                        <a href="{{ url('/transactions/serviceV/edit/' . $serv->serVId) }}" class="btn btn-primary">
                                                            Edit
                                                        </a>  
                                                    @endif
                                                    @if(auth()->user()->hasRole(['admin']))                                   
                                                        <form action="{{ url('transactions/serviceV/delete', $serv->serVId) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('POST') 
                                                            <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Are you sure?')">
                                                                Hapus   
                                                            </button>
                                                        </form>
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
    new DataTable('#dataTableServices');
</script>
@endpush
@endsection

@extends('admin.template.template')
@section('title', 'Barang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Barang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">barang</li>
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
                    <div class="card-header"><h3 class="card-title">Barang</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/commodities/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('transactions/commodities/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableCommodity" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori Barang</th>
                                        <th>Ruangan</th>
                                        <th>Merk</th>
                                        <th>Tipe Barang</th>
                                        <th>Jumlah Barang</th>
                                        <th>Satuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commodity as $com)
                                    <tr class="{{ $com->stock->sum('quantity') == 0 ? 'table-danger' : '' }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$com->image) }}" alt="Gambar Barang" style="max-width: 150px" class="object-cover rounded border border-gray-300">
                                            </div>
                                        </td>
                                        <td>{{ $com->name }}</td>
                                        <td>{{ $com->category ? $com->category->name : 'kategori tidak diatur' }}</td>
                                        <td>{{ $com->room ? $com->room->roomName : 'ruangan tidak diatur' }} | lt{{ $com->room ? $com->room->floor : 'floor not set' }}</td>
                                        <td>{{ $com->merk  ?? 'merk tidak diatur' }}</td>
                                        <td>{{ $com->typeText  ?? 'tipe tidak diatur' }}</td>
                                        <td>
                                            @if($com->stock->isNotEmpty())
                                                @php
                                                    $totalQuantity = $com->stock->sum('quantity');
                                                @endphp
                                                {{ $totalQuantity }}
                                                @if($totalQuantity == 0)
                                                    <span class="text-danger">- Stok barang habis!</span>
                                                @endif
                                            @else
                                                Stock not set
                                            @endif
                                        </td>
                                        <td>{{ $com->unit ? $com->unit->abbreviation : 'unit not set' }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ url('/transactions/commodities/details/' . $com->commoditiesId) }}" class="btn btn-primary">
                                                    Detail
                                                </a>     
                                                @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))                             
                                                <a href="{{ url('/transactions/commodities/edit/' . $com->commoditiesId) }}" class="btn btn-primary">
                                                    Edit
                                                </a>  
                                                @endif
                                                @if(auth()->user()->hasRole(['admin']))                                   
                                                <form action="{{ url('transactions/commodities/delete', $com->commoditiesId) }}" method="POST" style="display: inline;">
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
    new DataTable('#dataTableCommodity');
</script>
@endpush
@endsection

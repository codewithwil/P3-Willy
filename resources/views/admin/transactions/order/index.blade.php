@extends('admin.template.template')
@section('title', 'Pemesanan Jasa Loundry')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Pemesanan Jasa Loundry</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Pemesanan Jasa Loundry</li>
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
                    <div class="card-header"><h3 class="card-title">Pemesanan Jasa Loundry</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('transactions/order/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="dataTableShift" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Cabang</th>
                                    <th>Nama Pemesan</th>
                                    <th>Jasa yang dipesan</th>
                                    <th>Satuan Atau Berat</th>
                                    <th>Catatan</th>
                                    <th>Jenis Pengantaran</th>
                                    <th>Ongkir</th>
                                    <th>Total</th>
                                    <th>Status Order</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $s)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y')}}</td>
                                    <td>{{ $s->branch->address  }}</td>
                                    <td>{{ $s->customer->name  }}</td>
                                    <td>{{ $s->service->name  }}</td>
                                    <td>{{ $s->weight  }}</td>
                                    <td>{{ $s->note  }}</td>
                                    <td>{{ $s->deliverOp_label  }}</td>
                                    <td>{{ $s->postage  }}</td>
                                    <td>{{ $s->total  }}</td>
                                    <td>{{ $s->status_label  }}</td>
                                    <td>
                                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                                            <div class="d-flex gap-2">
                                                <a href="{{ url('/transactions/order/edit/' . $s->serviceTransId) }}" 
                                                    class="btn btn-primary">
                                                    Edit
                                                </a>     
                                                <a href="{{ url('/transactions/order/details/' . $s->serviceTransId) }}" 
                                                    class="btn btn-secondary">
                                                    Detail
                                                </a>                                
                                            </div>
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


@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#dataTableShift');
    </script>
@endpush
@endsection
@extends('admin.template.template')
@section('title', 'Cabang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Cabang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Resources</li>
                    <li class="breadcrumb-item active" aria-current="page">Cabang</li>
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
                    <div class="card-header"><h3 class="card-title">Cabang</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                        <a href="{{ url('setting/branch/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('setting/branch/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="dataTableShift" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Cabang</th>
                                    <th>Email Cabang</th>
                                    <th>Nomor Telepon</th>
                                    <th>Alamat Cabang</th>
                                    <th>Jam operasional</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branch as $b)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>{{ $b->branchName  }}</td>
                                    <td>{{ $b->email  }}</td>
                                    <td>{{ $b->phone  }}</td>
                                    <td>{{ $b->address  }}</td>
                                    <td>{{ $b->operationalHours  }}</td>
                                    <td>
                                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                                            <a href="{{ url('/setting/branch/edit/' . $b->branchId) }}" 
                                                class="btn btn-primary">
                                                Edit
                                            </a>                                   
                                        @endif
                                        @if(auth()->user()->hasRole(['admin']))
                                            <form action="{{ url('setting/branch/delete', $b->branchId) }}"
                                                 method="POST" 
                                                 style="display: inline;"
                                            >
                                                @csrf
                                                @method('POST') 
                                                <button type="submit" 
                                                class="btn btn-danger text-light hover:text-red-700" 
                                                onclick="return confirm('Are you sure?')"
                                                >Hapus</button>
                                            </form>
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
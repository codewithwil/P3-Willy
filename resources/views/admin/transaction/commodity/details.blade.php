@extends('admin.template.template')
@section('title', 'Detail Barang')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Barang: {{ $commodity->name }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/commodities') }}">Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $commodity->name }}</li>
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
                    <div class="card-header"><h3 class="card-title">Detail Barang: {{ $commodity->name }}</h3></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Gambar Barang</th>
                                <td>  
                                    @if(isset($commodity->image))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$commodity->image) }}" alt="Gambar Barang" style="max-width: 300px" class=" object-cover rounded border border-gray-300">
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td>{{ $commodity->name ?? 'nama barang belum di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Kategori Barang</th>
                                <td>{{ $commodity->category ? $commodity->category->name : 'Kategori belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Ruangan</th>
                                <td>{{ $commodity->room ? $commodity->room->roomName : 'Ruangan belum diatur' }} | lt{{ $commodity->room ? $commodity->room->floor : 'Lantai belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Tipe barang</th>
                                <td>{{ $commodity->typeText ?? 'tipe barang belum di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Merk Barang</th>
                                <td>{{ $commodity->merk ?? 'merk barang belum di atur'}}</td>
                            </tr>
                            <tr>
                                <th>Harga Barang</th>
                                <td>{{ 'Rp ' . number_format($commodity->price, 0, ',', '.')}}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Barang Tersedia</th>
                                <td>
                                    @if($commodity->stock->isNotEmpty())
                                        @php
                                            $totalQuantity = $commodity->stock->sum('quantity');
                                        @endphp
                                        {{ $totalQuantity }}
                                        @if($totalQuantity == 0)
                                            <span class="text-danger">- Stok barang habis!</span>
                                        @endif
                                    @else
                                        Stok belum diatur
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Satuan</th>
                                <td>{{ $commodity->unit ? $commodity->unit->abbreviation : 'Satuan belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi Barang</th>
                                <td>{{ $commodity->desc ?? 'deskripsi belum di atur' }}</td>
                            </tr>
                            <tr>
                                <th>Status Barang</th>
                                <td>{{ $commodity->statusText ?? 'Deskripsi belum diatur' }}</td>
                            </tr>
                            <tr>
                                <th>Informasi Tambahan</th>
                                <td>Dibuat Pada: {{ \Carbon\Carbon::parse($commodity->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Diperbarui Pada: {{ \Carbon\Carbon::parse($commodity->updated_at)->format('d/m/Y') }}</td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection

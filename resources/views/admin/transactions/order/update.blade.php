@extends('admin.template.template')
@section('title', 'edit Data Pemesanan Jasa')
@section('content')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />
<style>
    #map { height: 350px; width: 100%; margin-bottom: 20px; }
    .leaflet-control-geosearch {
        z-index: 1000;
        position: absolute;
        top: 10px;
        left: 10px;
    }
</style>
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Pemesanan Jasa</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Pemesanan Jasa</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('transactions/order/update/'.$order->serviceTransId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Pemesanan Jasa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Nama Pemesan</label>
                                    <input type="text" name="customer_id" id="customer_id" class="form-control" value="{{ $order->customer->name }}" readonly>  
                                </div>
                                <div class="mb-3" id="branchDiv">
                                    <label for="branch_id" class="form-label">Cabang</label>
                                    <textarea name="branch_id" id="branch_id" cols="30" rows="5" class="form-control" readonly>{{ $order->branch->address }}</textarea>                       
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Layanan</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $order->service->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Berat/Satuan</label>
                                    <div class="input-group">
                                        <input type="number" min="1" name="weight" id="weight" class="form-control" value="{{ $order->weight }}" readonly>
                                        <span class="input-group-text">{{ $order->service->unitType }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="postage" class="form-label">Ongkir</label>
                                    <input type="number" name="postage" class="form-control" id="postage"  value="{{ $order->postage }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="note" class="form-label">Catatan</label>
                                    <textarea name="note" id="note" cols="30" rows="4" class="form-control" readonly>{{ $order->note }}</textarea>
                                </div>   
                                <div class="mb-3">
                                    <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                                    <input type="text" name="paymentMethod" class="form-control" id="paymentMethod"  value="{{ $order->payment_label }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="deliverOption" class="form-label">Metode Pengantaran</label>
                                    <input type="text" name="deliverOption" class="form-control" id="deliverOption"  value="{{ $order->deliverOp_label }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="number" name="total" class="form-control" id="total"  value="{{ $order->total }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Order</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="0" {{ old('status', $order->status ?? '') == '0' ? 'selected' : '' }}>Dibatalkan</option>
                                        <option value="1" {{ old('status', $order->status ?? '') == '1' ? 'selected' : '' }}>Pending</option>
                                        <option value="2" {{ old('status', $order->status ?? '') == '2' ? 'selected' : '' }}>Proses</option>
                                        <option value="3" {{ old('status', $order->status ?? '') == '3' ? 'selected' : '' }}>Diantar</option>
                                        <option value="4" {{ old('status', $order->status ?? '') == '4' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="5" {{ old('status', $order->status ?? '') == '5' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    
                                </div>
                            </div>
                        </div>  
                                <!-- Tombol Submit -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>         
    </div>
</div>

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/bundle.min.js"></script>


@endpush
@endsection
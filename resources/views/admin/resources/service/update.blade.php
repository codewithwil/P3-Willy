    @extends('admin.template.template')
    @section('title', 'edit Data Layanan')
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
                <div class="col-sm-6"><h3 class="mb-0">Edit Data Layanan</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item">Setting</li>
                        <li class="breadcrumb-item active" aria-current="page">Layanan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <form class="row g-3" action="{{ url('setting/service/update/'.$service->serviceId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-4" style="border-left: 5px solid #007bff;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Data Layanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3" id="branchDi  v">
                                        <label for="branch" class="form-label">Cabang</label>
                                        <select name="branch_id" class="form-control" id="branch_id">
                                            <option value="">--- Pilih Cabang ---</option>
                                            @foreach ($branch as $b)
                                                <option value="{{ $b->branchId }}" {{ $b->branchId == $service->branch_id ? 'selected' : '' }}>
                                                    {{ $b->address }}
                                                </option>
                                            @endforeach
                                        </select>                                  
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Layanan</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $service->name }}" placeholder="Masukkan nama layanan">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pricePerUnit" class="form-label">Harga Per satuan</label>
                                        <input type="number" min="1" name="pricePerUnit" id="pricePerUnit" class="form-control"  value="{{ $service->pricePerUnit }}"  placeholder="Masukkan harga per satuan layanan">
                                    </div>
                                    <div class="mb-3">
                                        <label for="unitType" class="form-label">Satuan *kg, pcs dll</label>
                                        <input type="text" min="0" name="unitType" class="form-control" id="unitType"  value="{{ $service->unitType }}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="minQuantity" class="form-label">Jumlah Minimal</label>
                                        <input type="number" min="1" name="minQuantity" class="form-control" id="minQuantity"  value="{{ $service->minQuantity }}" >
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea name="description" id="description" cols="30" rows="4" class="form-control">{{ $service->description }}</textarea>
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
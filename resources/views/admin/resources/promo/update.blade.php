    @extends('admin.template.template')
    @section('title', 'edit Data Promo')
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
                <div class="col-sm-6"><h3 class="mb-0">Edit Data Promo</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item">Setting</li>
                        <li class="breadcrumb-item active" aria-current="page">Promo</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <form class="row g-3" action="{{ url('setting/promo/update/'.$promo->promoId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-4" style="border-left: 5px solid #007bff;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Data Promo</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="target_audience" class="form-label">Target Diskon</label>
                                        <select name="target_audience" id="target_audience" class="form-control" onchange="toggleBranchVisibility()">
                                            <option value="">--- Pilih Target Diskon ---</option>
                                            <option value="1" @if($promo->target_audience == 1) selected @endif>Member</option>
                                            <option value="2" @if($promo->target_audience == 2) selected @endif>Cabang</option>
                                        </select>                                        
                                    </div>
                                    
                                    <div class="mb-3" id="branchDiv" style="display: none;">
                                        <label for="branch" class="form-label">Cabang *jika ingin promo berdasarkan cabang</label>
                                        <select name="branch_id" class="form-control" id="branch_id">
                                            <option value="">--- Pilih Cabang ---</option>
                                            @foreach ($branch as $b)
                                                <option value="{{ $b->branchId }}" {{ $b->branchId == $promo->branch_id ? 'selected' : '' }}>
                                                    {{ $b->address }}
                                                </option>
                                            @endforeach
                                        </select>                                  
                                    </div>
                                    
                                    <div class="mb-3">
                                        {{-- <label for="promoCode" class="form-label">Kode Promo</label> --}}
                                        <input type="hidden" name="promoCode" class="form-control" id="promoCode" value="{{ $promo->promoCode }}" readonly>
                                    </div>                            
                                    
                                    <div class="mb-3">
                                        <label for="promoName" class="form-label">Nama Promo</label>
                                        <input type="text" name="promoName" id="promoName" class="form-control" value="{{ $promo->promoName }}"  placeholder="Masukkan nama promo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="typePromo" class="form-label">Jenis Promo</label>
                                        <select name="typePromo" id="typePromo" class="form-control">
                                            <option value="1"  @if($promo->typePromo == 1) selected @endif>Pesentase</option>
                                            <option value="2"  @if($promo->typePromo == 2) selected @endif>Nominal</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="amountPromo" class="form-label">Jumlah Promo</label>
                                        <input type="number" min="0" name="amountPromo" class="form-control" id="amountPromo" value="{{ $promo->amountPromo }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="startDate" class="form-label">Tanggal Mulai Promo</label>
                                        <input type="date" name="startDate" class="form-control" id="startDate" value="{{ $promo->startDate }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="endDate" class="form-label">Tanggal Berakhir Promo</label>
                                        <input type="date" name="endDate" class="form-control" id="endDate" value="{{ $promo->endDate }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea name="description" id="description" cols="30" rows="4" class="form-control">{{ $promo->description }}</textarea>
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

    <script>
        function toggleBranchVisibility() {
            let target_audience = document.getElementById('target_audience').value;
            let branchDiv = document.getElementById('branchDiv');
    
            if (target_audience == '2') {
                branchDiv.style.display = 'block';  
            } else {
                branchDiv.style.display = 'none';  
            }
        }
    
        document.addEventListener('DOMContentLoaded', function() {
            toggleBranchVisibility();
        });
    
    </script>
    @endpush
    @endsection
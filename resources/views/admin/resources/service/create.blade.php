@extends('admin.template.template')
@section('title', 'Tambah Data Layanan')
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
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Layanan</h3></div>
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
                                    @if (Auth::user()->branch)
                                        <option value="{{ Auth::user()->branch->branchId }}" selected>
                                            {{ Auth::user()->branch->address }}
                                        </option>
                                    @else
                                        @foreach ($branch as $b)
                                            <option value="{{ $b->branchId }}">{{ $b->address }}</option>
                                        @endforeach
                                    @endif
                                </select>                                  
                            </div>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Layanan</label>
                                <input type="text" name="name" id="name" class="form-control"  placeholder="Masukkan nama layanan">
                            </div>
                            <div class="mb-3">
                                <label for="pricePerUnit" class="form-label">Harga Per satuan</label>
                                <input type="number" min="1" name="pricePerUnit" id="pricePerUnit" class="form-control"  placeholder="Masukkan harga per satuan layanan">
                            </div>
                            <div class="mb-3">
                                <label for="unitType" class="form-label">Satuan *kg, pcs dll</label>
                                <input type="text" min="0" name="unitType" class="form-control" id="unitType">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="minQuantity" class="form-label">Jumlah Minimal</label>
                                <input type="number" min="1" name="minQuantity" class="form-control" id="minQuantity">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" cols="30" rows="4" class="form-control"></textarea>
                            </div>   
                        </div>
                    </div>    
                    <button type="button" class="btn btn-success mb-4" onclick="tambahService()">Tambah</button>        
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Layanan</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="promoTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 15%;">Cabang</th>
                                            <th style="width: 15%;">Nama Layanan</th>
                                            <th style="width: 15%;">Satuan</th>
                                            <th style="width: 15%;">Harga Satuan</th>
                                            <th style="width: 15%;">Jumlah Minimal</th>
                                            <th style="width: 15%;">Deskripsi</th>
                                            <th style="width: 15%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                  
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-md btn-success fwbold mt-3 shadow-sm" onclick="simpanSemua()">
                                <i class="fas fa-save me-2"></i> Simpan Semua
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/bundle.min.js"></script>
    <script>
        let serviceList = [];
    
        function tambahService() {
            let branchSelect = document.getElementById('branch_id');
            let branch_id = branchSelect.value.trim();
            let branch_name = branchSelect.options[branchSelect.selectedIndex].text.trim();

            let name = document.getElementById('name').value.trim();
            let pricePerUnit = document.getElementById('pricePerUnit').value.trim();
            let unitType = document.getElementById('unitType').value.trim();
            let minQuantity = document.getElementById('minQuantity').value.trim();
            let description = document.getElementById('description').value.trim();
    
            if (!name || !pricePerUnit || !unitType || !minQuantity) {
                alert("Semua field (kecuali deskripsi) harus diisi!");
                return;
            }
    
            let newService = {
                id: Date.now(),
                branch_id,
                branch_name,
                name,
                pricePerUnit,
                unitType,
                minQuantity,
                description
            };
    
            serviceList.push(newService);
    
            // Reset form
            document.getElementById('branch_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('pricePerUnit').value = '';
            document.getElementById('unitType').value = '';
            document.getElementById('minQuantity').value = '';
            document.getElementById('description').value = '';
    
            renderServiceTable();
        }
    
        function renderServiceTable() {
            let tbody = document.querySelector("#promoTable tbody");
            tbody.innerHTML = '';
    
            serviceList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.branch_name || '-'}</td>
                        <td>${item.name}</td>
                        <td>${item.unitType}</td>
                        <td>Rp ${parseInt(item.pricePerUnit).toLocaleString()}</td>
                        <td>${item.minQuantity}</td>
                        <td>${item.description || '-'}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusService(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }
    
        function hapusService(id) {
            serviceList = serviceList.filter(item => item.id !== id);
            renderServiceTable();
        }
    
        function simpanSemua() {
            if (serviceList.length === 0) {
                alert("Tidak ada data layanan untuk disimpan!");
                return;
            }
    
            let requests = serviceList.map(item => {
                let formData = new FormData();
                formData.append('branch_id', item.branch_id);
                formData.append('name', item.name);
                formData.append('pricePerUnit', item.pricePerUnit);
                formData.append('unitType', item.unitType);
                formData.append('minQuantity', item.minQuantity);
                formData.append('description', item.description);
    
                return fetch("{{ url('setting/service/store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    console.log("Sukses:", result);
                })
                .catch(error => {
                    console.error("Gagal menyimpan layanan:", error);
                });
            });
    
            Promise.all(requests).then(() => {
                serviceList = [];
                renderServiceTable();
                alert("Semua layanan berhasil disimpan!");
                window.location.href = "/setting/service/";
            });
        }
    </script>
    
@endpush

@endsection

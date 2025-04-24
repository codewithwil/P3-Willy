@extends('admin.template.template')
@section('title', 'Tambah Data Promo')
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
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Promo</h3></div>
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
                                    <option value="1">Member</option>
                                    <option value="2">Cabang</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="branchDiv" style="display: none;">
                                <label for="branch" class="form-label">Cabang *jika ingin promo berdasarkan cabang</label>
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
                                {{-- <label for="promoCode" class="form-label">Kode Promo</label> --}}
                                <input type="hidden" name="promoCode" class="form-control" id="promoCode" readonly>
                            </div>                            
                            
                            <div class="mb-3">
                                <label for="promoName" class="form-label">Nama Promo</label>
                                <input type="text" name="promoName" id="promoName" class="form-control"  placeholder="Masukkan nama promo">
                            </div>
                            <div class="mb-3">
                                <label for="typePromo" class="form-label">Jenis Promo</label>
                                <select name="typePromo" id="typePromo" class="form-control">
                                    <option value="1">Pesentase</option>
                                    <option value="2">Nominal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amountPromo" class="form-label">Jumlah Promo</label>
                                <input type="number" min="0" name="amountPromo" class="form-control" id="amountPromo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Tanggal Mulai Promo</label>
                                <input type="date" name="startDate" class="form-control" id="startDate">
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Tanggal Berakhir Promo</label>
                                <input type="date" name="endDate" class="form-control" id="endDate">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" cols="30" rows="4" class="form-control"></textarea>
                            </div>   
                        </div>
                    </div>    
                    <button type="button" class="btn btn-success mb-4" onclick="tambahPromo()">Tambah</button>        
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Promo</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="promoTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 15%;">Kode Promo</th>
                                            <th style="width: 15%;">Target Diskon</th>
                                            <th style="width: 15%;">Nama Promo</th>
                                            <th style="width: 15%;">Deskripsi</th>
                                            <th style="width: 15%;">Tanggal mulai</th>
                                            <th style="width: 15%;">Tanggal Akhir</th>
                                            <th style="width: 15%;">Jenis Promo</th>
                                            <th style="width: 15%;">Jumlah Promo</th>
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
<script>
    let promoList = [];
    let promoNumberStart = {{ $lastNumber ?? 0 }};  

    function tambahPromo() {
        let target_audience = document.getElementById('target_audience').value.trim();
        let branch_id = document.getElementById('branch_id').value.trim();
        let promoName = document.getElementById('promoName').value.trim();
        let description = document.getElementById('description').value.trim();
        let startDate = document.getElementById('startDate').value.trim();
        let endDate = document.getElementById('endDate').value.trim();
        let typePromo = document.getElementById('typePromo').value;
        let amountPromo = document.getElementById('amountPromo').value.trim();

        if (!promoName || !startDate || !endDate || !amountPromo) {
            alert("Semua field harus diisi!");
            return;
        }

        promoNumberStart++;  
        let promoCode = 'PRMO' + String(promoNumberStart).padStart(3, '0'); 

        document.getElementById('promoCode').value = promoCode; 

        let newPromo = {
            id: Date.now(),
            promoCode,
            target_audience,
            branch_id,
            promoName,
            description,
            startDate,
            endDate,
            typePromo,
            amountPromo
        };

        promoList.push(newPromo); 

        document.getElementById('target_audience').value = '';
        document.getElementById('promoName').value = '';
        document.getElementById('description').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('typePromo').value = '1';
        document.getElementById('amountPromo').value = '';

        renderPromoTable(); 
    }

    function renderPromoTable() {
        let tbody = document.querySelector("#promoTable tbody");
        tbody.innerHTML = '';

        promoList.forEach((item, index) => {
            let typeText = item.typePromo === "1" ? "Persentase" : "Nominal";
            let jumlahText = item.typePromo === "1" ? item.amountPromo + "%" : "Rp" + parseInt(item.amountPromo).toLocaleString();

            let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.promoCode}</td>
                    <td>${item.target_audience}</td>
                    <td>${item.promoName}</td>
                    <td>${item.description}</td>
                    <td>${item.startDate}</td>
                    <td>${item.endDate}</td>
                    <td>${typeText}</td>
                    <td>${jumlahText}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="hapusPromo(${item.id})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function hapusPromo(id) {
        promoList = promoList.filter(item => item.id !== id);
        renderPromoTable();
    }

    function simpanSemua() {
        if (promoList.length === 0) {
            alert("Tidak ada data promo untuk disimpan!");
            return;
        }

        let requests = promoList.map(item => {
            let formData = new FormData();
            formData.append('promoCode', item.promoCode);
            formData.append('target_audience', item.target_audience);
            formData.append('branch_id', item.branch_id);
            formData.append('promoName', item.promoName);
            formData.append('description', item.description);
            formData.append('startDate', item.startDate);
            formData.append('endDate', item.endDate);
            formData.append('typePromo', item.typePromo);
            formData.append('amountPromo', item.amountPromo);

            return fetch("{{ url('setting/promo/store') }}", {
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
                console.error("Gagal menyimpan promo:", error);
            });
        });

        Promise.all(requests).then(() => {
            promoList = [];
            renderPromoTable();
            alert("Semua promo berhasil disimpan!");
            window.location.href = "/setting/promo/";
        });
    }

</script>

@endpush

@endsection

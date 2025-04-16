@extends('admin.template.template')
@section('title', 'Tambah Data Cabang')
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
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Cabang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Cabang</li>
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
                    <h5 class="mb-0">Data Cabang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="company_id" class="form-label">Nama Perusahaan</label>
                                <select name="company_id" class="form-control" id="company_id" disabled>
                                    <option value="{{ $company->companyId }}">{{ $company->name }}</option>
                                </select>
                                
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Cabang</label>
                                <input type="email" name="email" class="form-control" id="email"  placeholder="Masukkan email cabang">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon Cabang</label>
                                <input type="number" name="phone" class="form-control" id="phone"  placeholder="Masukkan nomor telepon cabang">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Lokasi di Peta</label>
                                <div id="map"></div>
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Cabang</label>
                                <textarea name="address" id="address" cols="30" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="operationalHours" class="form-label">Jam Operasional</label>
                                <input type="text" name="operationalHours" class="form-control" id="operationalHours"  placeholder="Masukkan jam operasional cabang">
                            </div>
                            <div class="mb-3">
                                <label for="ltd" class="form-label">Latitude</label>
                                <input type="text" name="ltd" class="form-control" id="ltd">
                            </div>
                            <div class="mb-3">
                                <label for="lng" class="form-label">Longitude</label>
                                <input type="text" name="lng" class="form-control" id="lng">
                            </div>
                        </div>
                    </div>    
                    <button type="button" class="btn btn-success mb-4" onclick="tambahCabang()">Tambah</button>        
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Cabang</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="branchTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 15%;">Nama</th>
                                            <th style="width: 15%;">Email</th>
                                            <th style="width: 15%;">Telepon</th>
                                            <th style="width: 15%;">Alamat</th>
                                            <th style="width: 15%;">Jam Operasional</th>
                                            <th style="width: 15%;">Latitude</th>
                                            <th style="width: 15%;">Longtude</th>
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
    document.addEventListener("DOMContentLoaded", function () {
        const defaultLat = -6.200000;
        const defaultLng = 106.816666;

        const map = L.map('map').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng]).addTo(map);

        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);

            document.getElementById('ltd').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);
        });

        const search = new window.GeoSearch.GeoSearchControl({
            provider: new window.GeoSearch.OpenStreetMapProvider(),
            style: 'bar',
            autoComplete: true,
            autoCompleteDelay: 250,
            showMarker: false,
            retainZoomLevel: false,
            animateZoom: true,
            keepResult: true
        });
        map.addControl(search);

        map.on('geosearch/showlocation', function(result) {
            const ltd = result.location.y;
            const lng = result.location.x;

            marker.setLatLng([ltd, lng]);
            map.setView([ltd, lng], 15);

            document.getElementById('ltd').value = ltd.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);
        });
    });

    let cabangList = [];

    function tambahCabang() {
        let companySelect = document.getElementById('company_id'); 
        let company_id = companySelect.value.trim();
        let company_name = companySelect.options[companySelect.selectedIndex].text;
        let email = document.getElementById('email').value.trim();
        let phone = document.getElementById('phone').value.trim();
        let address = document.getElementById('address').value.trim();
        let operationalHours = document.getElementById('operationalHours').value.trim();
        let ltd = document.getElementById('ltd').value.trim();
        let lng = document.getElementById('lng').value.trim();

        if (!company_id || !email || !phone || !address || !operationalHours || !ltd || !lng) {
            alert("Semua field harus diisi!");
            return;
        }

        let newData = {
            id: Date.now(),
            company_id,
            company_name,
            email,
            phone,
            address,
            operationalHours,
            ltd,
            lng
        };

        cabangList.push(newData);

        document.getElementById('email').value = '';
        document.getElementById('phone').value = '';
        document.getElementById('address').value = '';
        document.getElementById('operationalHours').value = '';
        document.getElementById('ltd').value = '';
        document.getElementById('lng').value = '';

        renderTable();
    }

    function renderTable() {
        let tbody = document.querySelector("#branchTable tbody");
        tbody.innerHTML = '';

        cabangList.forEach((item, index) => {
            let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.company_name}</td>
                    <td>${item.email}</td>
                    <td>${item.phone}</td>
                    <td>${item.address}</td>
                    <td>${item.operationalHours}</td>
                    <td>${item.ltd}</td>
                    <td>${item.lng}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="hapusCabang(${item.id})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function hapusCabang(id) {
        cabangList = cabangList.filter(item => item.id !== id);
        renderTable();
    }

    function simpanSemua() {
        if (cabangList.length === 0) {
            alert("Tidak ada data untuk disimpan!");
            return;
        }

        let requests = cabangList.map(item => {
            let formData = new FormData();
            formData.append('company_id', item.company_id);
            formData.append('email', item.email);
            formData.append('phone', item.phone);
            formData.append('address', item.address);
            formData.append('operationalHours', item.operationalHours);
            formData.append('ltd', item.ltd);
            formData.append('lng', item.lng);

            return fetch("{{ url('setting/branch/store') }}", {
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
                console.error("Gagal menyimpan cabang:", error);
            });
        });

        Promise.all(requests).then(() => {
            cabangList = [];
            renderTable();
            alert("Semua data cabang berhasil disimpan!");
            window.location.href = "/setting/branch/";
        });
    }
</script>
@endpush

@endsection

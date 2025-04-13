@extends('admin.template.template')
@section('title', 'tambah Tipe Kendaraan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Tipe kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Tipe kendaraan</li>
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
                    <h5 class="mb-0">Data Tipe kendaraan</h5>
                </div>
                <div class="card-body">
                    <!-- Nama Data user Email -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Jenis Kendaraan</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">--- Jenis Kendaraan ---</option>
                                    <option value="1">Motor</option>
                                    <option value="2">Mobil</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tipe Kendaraan</label>
                                <input type="text" name="name" class="form-control" id="name"  placeholder="Masukkan Tipe Kendaraan">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mb-4" onclick="tambahTypeV()">Tambah</button>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Tipe Kendaraan</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="typeVTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 40%;">jenis Kendaraan</th>
                                            <th style="width: 40%;">Tipe Kendaraan</th>
                                            <th style="width: 20%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-md btn-success  fwbold mt-3 shadow-sm" onclick="simpanSemua()">
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
    <script>
        let typeList = [];

        function tambahTypeV() {
            let name = document.getElementById('name').value.trim();
            let type = document.getElementById('type').value.trim();

            if (name === '' || type === '') {
                alert("jenis kendaraan dan tipe kendaraan tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), name: name, type:type };
            typeList.push(newData);
            document.getElementById('name').value = ''; 
            document.getElementById('type').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#typeVTable tbody");
            tbody.innerHTML = '';

            typeList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <select class="form-control" onchange="editTipeKendaraan(${item.id}, 'type',this.value)">
                                <option value="1" ${item.type === '1' ? 'selected' : ''}>Motor</option>
                                <option value="2" ${item.type === '2' ? 'selected' : ''}>Mobil</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editTipeKendaraan(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusTipeKendaraan(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editTipeKendaraan(id, newValue) {
            let typeV = typeList.find(item => item.id === id);
            if (typeV) {
                typeV.name = newValue;
                typeV.type = newValue;
            }
        }

        function hapusTipeKendaraan(id) {
            typeList = typeList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (typeList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = typeList.map(item => {
                let formData = new FormData();
                formData.append('type', item.type);
                formData.append('name', item.name);

                return fetch("{{ url('configuration/typeVehicle/store') }}", {
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
                    console.error("Gagal menyimpan typeV:", error);
                });
            });

            Promise.all(requests).then(() => {
                typeList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/configuration/typeVehicle/"; 
            });
        }

    </script>
@endpush
@endsection
@extends('admin.template.template')
@section('title', 'tambah data gudang')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Gudang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item">Manajemen Ruangan</li>
                    <li class="breadcrumb-item active" aria-current="page">Gudang</li>
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
                    <h5 class="mb-0">Data Gudang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="buildingName" class="form-label">Nama Gudang</label>
                                <input type="text" name="buildingName" class="form-control" id="buildingName"  placeholder="Masukkan Nama Gudang">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" onclick="tambahGudang()">Tambah</button>
                </div>
                  <!-- Tabel sementara -->
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Gudang</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="gudangTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 60%;">Nama Gudang</th>
                                            <th style="width: 30%;">Aksi</th>
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
        let gudangList = [];

        function tambahGudang() {
            let buildingName = document.getElementById('buildingName').value.trim();

            if (buildingName === '') {
                alert("Nama Gudang tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), buildingName: buildingName };
            gudangList.push(newData);
            document.getElementById('buildingName').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#gudangTable tbody");
            tbody.innerHTML = '';

            gudangList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.buildingName}" onchange="editGudang(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusGudang(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editGudang(id, newValue) {
            let building = gudangList.find(item => item.id === id);
            if (building) {
                building.buildingName = newValue;
            }
        }

        function hapusGudang(id) {
            gudangList = gudangList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (gudangList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = gudangList.map(item => {
                let formData = new FormData();
                formData.append('buildingName', item.buildingName);

                return fetch("{{ url('configuration/building/store') }}", {
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
                    console.error("Gagal menyimpan building:", error);
                });
            });

            Promise.all(requests).then(() => {
                gudangList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/configuration/building/"; 
            });
        }

    </script>
@endpush
@endsection
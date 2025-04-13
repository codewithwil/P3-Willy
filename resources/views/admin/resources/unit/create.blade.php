@extends('admin.template.template')
@section('title', 'tambah data Satuan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Satuan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Satuan</li>
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
                    <h5 class="mb-0">Data Satuan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nameUnit" class="form-label">Nama satuan</label>
                                <input type="text" name="name" class="form-control" id="name"  placeholder="Masukkan nama satuan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nameUnit" class="form-label">Singkatan Satuan</label>
                                <input type="text" name="abbreviation" class="form-control" id="abbreviation"  placeholder="Masukkan singkatan satuan">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mb-4" onclick="tambahSatuan()">Tambah</button>
                </div>
                  <!-- Tabel sementara -->
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Merk</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="satuanTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 40%;">Nama Satuan</th>
                                            <th style="width: 40%;">Singkatan Satuan</th>
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
        let satuanList = [];

        function tambahSatuan() {
            let name         = document.getElementById('name').value.trim();
            let abbreviation = document.getElementById('abbreviation').value.trim();

            if (name === '' || abbreviation === '') {
                alert("Nama satuan dan singkatan satuan tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), name: name, abbreviation:abbreviation };
            satuanList.push(newData);
            document.getElementById('name').value = ''; 
            document.getElementById('abbreviation').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody       = document.querySelector("#satuanTable tbody");
            tbody.innerHTML = '';

            satuanList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editSatuan(${item.id}, this.value)"></td>
                        <td><input type="text" class="form-control" value="${item.abbreviation}" onchange="editSatuan(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusSatuan(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editSatuan(id, newValue) {
            let satuan = satuanList.find(item => item.id === id);
            if (satuan) {
                satuan.name = newValue;
                satuan.abbreviation = newValue;
            }
        }

        function hapusSatuan(id) {
            satuanList = satuanList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (satuanList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = satuanList.map(item => {
                let formData = new FormData();
                formData.append('name', item.name);
                formData.append('abbreviation', item.abbreviation);

                return fetch("{{ url('configuration/unit/store') }}", {
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
                    console.error("Gagal menyimpan satuan:", error);
                });
            });

            Promise.all(requests).then(() => {
                satuanList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/configuration/unit/"; 
            });
        }

    </script>
@endpush
@endsection
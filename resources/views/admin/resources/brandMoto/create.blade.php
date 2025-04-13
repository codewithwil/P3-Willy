@extends('admin.template.template')
@section('title', 'tambah data merk motor')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Merk Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Merk Kendaraan</li>
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
                        <h5 class="mb-0">Data Merk Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Merk Kendaraan</label>
                                    <input type="text" name="name" class="form-control" id="name"  placeholder="Masukkan nama merk motor">
                                </div>
                                <button type="button" class="btn btn-success mb-4" onclick="tambahMerk()">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <!-- Tabel sementara -->
                    <div class="col-md-12">
                        <div class="card shadow-lg border-0 rounded">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Merk</h5>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered align-middle text-center" id="merkTable">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th style="width: 10%;">No</th>
                                                <th style="width: 60%;">Nama Merk Kendaraan</th>
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
        let merkList = [];

        function tambahMerk() {
            let name = document.getElementById('name').value.trim();

            if (name === '') {
                alert("Nama merk tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), name: name };
            merkList.push(newData);
            document.getElementById('name').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#merkTable tbody");
            tbody.innerHTML = '';

            merkList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editMerk(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusMerk(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editMerk(id, newValue) {
            let merk = merkList.find(item => item.id === id);
            if (merk) {
                merk.name = newValue;
            }
        }

        function hapusMerk(id) {
            merkList = merkList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (merkList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = merkList.map(item => {
                let formData = new FormData();
                formData.append('name', item.name);

                return fetch("{{ url('resources/brandMotor/store') }}", {
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
                    console.error("Gagal menyimpan merk:", error);
                });
            });

            Promise.all(requests).then(() => {
                merkList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/resources/brandMotor/"; 
            });
        }

    </script>
@endpush
@endsection
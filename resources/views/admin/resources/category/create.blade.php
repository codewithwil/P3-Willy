@extends('admin.template.template')
@section('title', 'Tambah Data Kategori')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Kategori</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Data Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nameCategory" class="form-label">Nama</label>
                            <input type="text" id="nameCategory" class="form-control" placeholder="Masukkan nama kategori">
                        </div>
                        <button type="button" class="btn btn-success" onclick="tambahKategori()">Tambah</button>
                    </div>
                </div>
            </div>

            <!-- Tabel sementara -->
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Kategori</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle text-center" id="kategoriTable">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th style="width: 10%;">No</th>
                                        <th style="width: 60%;">Nama Kategori</th>
                                        <th style="width: 30%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data kategori sementara -->
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

@push('js')
    <script>
        let kategoriList = [];

        function tambahKategori() {
            let nameCategory = document.getElementById('nameCategory').value.trim();

            if (nameCategory === '') {
                alert("Nama kategori tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), name: nameCategory };
            kategoriList.push(newData);
            document.getElementById('nameCategory').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#kategoriTable tbody");
            tbody.innerHTML = '';

            kategoriList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editKategori(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusKategori(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editKategori(id, newValue) {
            let kategori = kategoriList.find(item => item.id === id);
            if (kategori) {
                kategori.name = newValue;
            }
        }

        function hapusKategori(id) {
            kategoriList = kategoriList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (kategoriList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = kategoriList.map(item => {
                let formData = new FormData();
                formData.append('name', item.name);

                return fetch("{{ url('configuration/category/store') }}", {
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
                    console.error("Gagal menyimpan kategori:", error);
                });
            });

            Promise.all(requests).then(() => {
                kategoriList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/configuration/category/"; 
            });
        }

    </script>
@endpush
@endsection

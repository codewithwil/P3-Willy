@extends('admin.template.template')
@section('title', 'tambah data Supplier')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Supplier</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Supplier</li>
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
                    <h5 class="mb-0">Data Supplier</h5>
                </div>
                <div class="card-body">
                    <!-- Nama Data user Email -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Supplier</label>
                                <input type="text" name="name" class="form-control" id="name"  placeholder="Masukkan nama supplier">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"  placeholder="Masukkan email supplier">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="number" min="0" name="phone" class="form-control" id="phone"  placeholder="Masukan Nomor Telepon Supplier">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea name="address" id="address" cols="30" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mb-4" onclick="tambahSupplier()">Tambah</button>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Supplier</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="supplierTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 20%;">Nama Supplier</th>
                                            <th style="width: 20%;">Email Supplier</th>
                                            <th style="width: 20%;">Nomor Supplier</th>
                                            <th style="width: 20%;">Alamat Supplier</th>
                                            <th style="width: 10%;">Aksi</th>
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
        let supplierList = [];

        function tambahSupplier() {
            let name    = document.getElementById('name').value.trim();
            let phone   = document.getElementById('phone').value.trim();
            let email   = document.getElementById('email').value.trim();
            let address = document.getElementById('address').value.trim();

            if (name === '' || phone === '' || email === '' || address === '') {
                alert("Data harus diisi semua tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), name: name, phone:phone, email:email, address:address};
            supplierList.push(newData);
            document.getElementById('name').value = ''; 
            document.getElementById('phone').value = ''; 
            document.getElementById('email').value = ''; 
            document.getElementById('address').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#supplierTable tbody");
            tbody.innerHTML = '';

            supplierList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editSupplier(${item.id}, this.value)"></td>
                        <td><input type="number" min="0" class="form-control" value="${item.phone}" onchange="editSupplier(${item.id}, this.value)"></td>
                        <td><input type="email" class="form-control" value="${item.email}" onchange="editSupplier(${item.id}, this.value)"></td>
                        <td><input type="text" class="form-control" value="${item.address}" onchange="editSupplier(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusSupplier(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editSupplier(id, newValue) {
            let supplier = supplierList.find(item => item.id === id);
            if (supplier) {
                supplier.name = newValue;
                supplier.phone = newValue;
                supplier.email = newValue;
                supplier.address = newValue;
            }
        }

        function hapusSupplier(id) {
            supplierList = supplierList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (supplierList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = supplierList.map(item => {
                let formData = new FormData();
                formData.append('name', item.name);
                formData.append('phone', item.phone);
                formData.append('email', item.email);
                formData.append('address', item.address);

                return fetch("{{ url('configuration/supplier/store') }}", {
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
                    console.error("Gagal menyimpan supplier:", error);
                });
            });

            Promise.all(requests).then(() => {
                supplierList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/configuration/supplier/"; 
            });
        }

    </script>
@endpush

@endsection
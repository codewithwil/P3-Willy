@extends('admin.template.template')
@section('title', 'tambah data Shift')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Shift</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Resources</li>
                    <li class="breadcrumb-item active" aria-current="page">Shift</li>
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
                    <h5 class="mb-0">Data Shift</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shiftName" class="form-label">Nama Shift</label>
                                <input type="text" name="shiftName" class="form-control" id="shiftName"  placeholder="Masukkan nama shift">
                            </div>
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Waktu Mulai Shift</label>
                                <input type="time" name="start_time" class="form-control" id="start_time"  placeholder="Masukkan waktu mulai shift">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">Waktu Selesai Shift</label>
                                <input type="time" name="end_time" class="form-control" id="end_time"  placeholder="Masukkan waktu Selesai shift">
                            </div>
                        </div>
                    </div>    
                    <button type="button" class="btn btn-success mb-4" onclick="tambahShift()">Tambah</button>        
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Shift</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="shiftTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 25%;">Nama Shift</th>
                                            <th style="width: 25%;">Waktu Mulai</th>
                                            <th style="width: 25%;">Waktu Selesai</th>
                                            <th style="width: 15%;">Aksi</th>
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
        let shiftList = [];

        function tambahShift() {
            let shiftName  = document.getElementById('shiftName').value.trim();
            let start_time = document.getElementById('start_time').value.trim();
            let end_time   = document.getElementById('end_time').value.trim();

            if (shiftName === '' || start_time === '' || end_time === '') {
                alert("Nama shift dan singkatan shift tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), shiftName: shiftName, start_time:start_time, end_time:end_time };
            shiftList.push(newData);
            document.getElementById('shiftName').value  = ''; 
            document.getElementById('start_time').value = ''; 
            document.getElementById('end_time').value   = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#shiftTable tbody");
            tbody.innerHTML = '';

            shiftList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.shiftName}" onchange="editShift(${item.id}, this.value)"></td>
                        <td><input type="time" class="form-control" value="${item.start_time}" onchange="editShift(${item.id}, this.value)"></td>
                        <td><input type="time" class="form-control" value="${item.end_time}" onchange="editShift(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusShift(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editShift(id, newValue) {
            let shift = shiftList.find(item => item.id === id);
            if (shift) {
                shift.shiftName  = newValue;
                shift.start_time = newValue;
                shift.end_time   = newValue;
            }
        }

        function hapusShift(id) {
            shiftList = shiftList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (shiftList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = shiftList.map(item => {
                let formData = new FormData();
                formData.append('shiftName', item.shiftName);
                formData.append('start_time', item.start_time);
                formData.append('end_time', item.end_time);

                return fetch("{{ url('resources/shift/store') }}", {
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
                    console.error("Gagal menyimpan shift:", error);
                });
            });

            Promise.all(requests).then(() => {
                shiftList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/resources/shift/"; 
            });
        }

    </script>
@endpush

@endsection
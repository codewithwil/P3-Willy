@extends('admin.template.template')
@section('title', 'tambah data ruangan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Ruangan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item">Manajemen Ruangan</li>
                    <li class="breadcrumb-item active" aria-current="page">Ruangan</li>
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
                    <h5 class="mb-0">Data Ruangan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roomName" class="form-label">Nama Ruangan</label>
                                <input type="text" name="roomName" class="form-control" id="roomName"  placeholder="Masukkan Nama Ruangan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="building_Id" class="form-label">Gedung</label>
                                <select name="building_Id" class="form-control" id="building_Id">
                                    <option value="">--- Pilih Gedung ---</option>
                                    @foreach ($building as $b)  
                                        <option value="{{ $b->buildingId }}">{{ $b->buildingName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="floor" class="form-label">Lantai</label>
                                <input type="number" name="floor" class="form-control" id="floor"  placeholder="Masukkan Lantai">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" onclick="tambahRuangan()">Tambah</button>
                </div>
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
                                            <th style="width: 25%;">Nama Ruangan</th>
                                            <th style="width: 25%;">Gedung</th>
                                            <th style="width: 25%;">Lantai</th>
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
        let roomList = [];

        function tambahRuangan() {
            let roomName = document.getElementById('roomName').value.trim();
            let building_Id = document.getElementById('building_Id').value.trim();
            let floor = document.getElementById('floor').value.trim();

            if (roomName === '' || building_Id === '' || floor === '') {
                alert("Semua data wajib diisi tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), roomName: roomName, building_Id:building_Id, floor:floor};
            roomList.push(newData);
            document.getElementById('roomName').value = ''; 
            document.getElementById('building_Id').value = ''; 
            document.getElementById('floor').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#gudangTable tbody");
            tbody.innerHTML = '';

            roomList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.roomName}" onchange="editRuangan(${item.id}, this.value)"></td>
                       <td>
                            <select class="form-control" onchange="editRuangan(${item.id}, this.value)">
                                @foreach ($building as $b)  
                                    <option value="{{ $b->buildingId }}" ${item.building_Id == {{ $b->buildingId }} ? 'selected' : ''}>
                                        {{ $b->buildingName }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                          <td><input type="number" min="1" class="form-control" value="${item.floor}" onchange="editRuangan(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusRuangan(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editRuangan(id, newValue) {
            let building = roomList.find(item => item.id === id);
            if (building) {
                building.roomName = newValue;
                building.building_Id = newValue;
                building.floor = newValue;
            }
        }

        function hapusRuangan(id) {
            roomList = roomList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (roomList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = roomList.map(item => {
                let formData = new FormData();
                formData.append('roomName', item.roomName);
                formData.append('building_Id', item.building_Id);
                formData.append('floor', item.floor);

                return fetch("{{ url('configuration/rooms/store') }}", {
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
                roomList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/configuration/rooms/"; 
            });
        }

    </script>
@endpush
@endsection
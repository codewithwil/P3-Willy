@extends('admin.template.template')
@section('title', 'tambah data Shift pekerja')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Shift Pekerja</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Resources</li>
                    <li class="breadcrumb-item">Manajemen Shift Pekerja</li>
                    <li class="breadcrumb-item active" aria-current="page">Shift Pekerja</li>
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
                    <h5 class="mb-0">Data Shift Pekerja</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal Shift</label>
                                <input type="date" name="date" class="form-control" id="date"  placeholder="Masukkan tanggal">
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Pekerja</label>
                                <select name="user_id" class="form-control" id="user_id">
                                    <option value="">--- Pilih Pekerja ---</option>
                                    @foreach ($users as $us)  
                                        <option value="{{ $us->id }}">
                                            {{ $us->name }} 
                                            @foreach ($us->getRoleNames() as $role)
                                            - {{ $role }}
                                            @endforeach
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shift_id" class="form-label">Shift</label>
                                <select name="shift_id" id="shift_id" class="form-control">
                                    <option value="">--- Pilih Shift ---</option>
                                    @foreach ($shift as $s)
                                    <option value="{{ $s->shiftId }}">
                                        {{ $s->shiftName }} 
                                    </option>                                            
                                    @endforeach                            
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" onclick="tambahEmpShift()">Tambah</button>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Shift Pekerja</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="empShiftTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 10%;">No</th>
                                            <th style="width: 25%;">Tanggal Shift</th>
                                            <th style="width: 25%;">Pekerja</th>
                                            <th style="width: 25%;">Shift</th>
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
        let empShiftList = [];

        function tambahEmpShift() {
            let date = document.getElementById('date').value.trim();
            let shift_id = document.getElementById('shift_id').value.trim();
            let user_id = document.getElementById('user_id').value.trim();

            if (date === '' || shift_id === '' || user_id === '') {
                alert("Semua data wajib diisi tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), date: date, shift_id:shift_id, user_id:user_id};
            empShiftList.push(newData);
            document.getElementById('date').value = ''; 
            document.getElementById('user_id').value = ''; 
            document.getElementById('shift_id').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#empShiftTable tbody");
            tbody.innerHTML = '';

            empShiftList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.date}" onchange="editEmpShift(${item.id}, this.value)"></td>
                        <td>
                            <select class="form-control" onchange="editEmpShift(${item.id}, this.value)">
                                @foreach ($users as $us)  
                                    <option value="{{ $us->id }}" ${item.user_id == {{ $us->id }} ? 'selected' : ''}>
                                        {{ $us->name }} 
                                        @foreach ($us->getRoleNames() as $role)
                                        - {{ $role }}
                                        @endforeach
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control" onchange="editEmpShift(${item.id}, this.value)">
                                @foreach ($shift as $s)  
                                    <option value="{{ $s->shiftId }}" ${item.shift_id == {{ $s->shiftId }} ? 'selected' : ''}>
                                        {{ $s->shiftName }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                          <td><input type="number" min="1" class="form-control" value="${item.user_id}" onchange="editEmpShift(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusRuangan(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editEmpShift(id, newValue) {
            let building = empShiftList.find(item => item.id === id);
            if (building) {
                building.date = newValue;
                building.user_id = newValue;
                building.shift_id = newValue;
            }
        }

        function hapusRuangan(id) {
            empShiftList = empShiftList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (empShiftList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = empShiftList.map(item => {
                let formData = new FormData();
                formData.append('date', item.date);
                formData.append('shift_id', item.shift_id);
                formData.append('user_id', item.user_id);

                return fetch("{{ url('resources/empShift/store') }}", {
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
                empShiftList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/resources/empShift/"; 
            });
        }

    </script>
@endpush
@endsection
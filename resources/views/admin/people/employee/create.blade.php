@extends('admin.template.template')
@section('title', 'tambah akun user')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Petugas</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Petugas</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="card mb-4" style="border-left: 5px solid #007bff;">
                {{-- acount section   --}}
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Akun Petugas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"  placeholder="Masukkan Email">
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-control" disabled>
                                    <option value="">Pilih Role</option>
                                    <option value="petugas" selected>Petugas</option>
                                </select>
                                <input type="hidden" name="role" value="petugas">
                            </div>  
                            <div class="mb-3">
                                <label for="branch" class="form-label">Cabang</label>
                                <select name="branch_id" class="form-control" id="branch_id">
                                    <option value="">--- Pilih Cabang ---</option>
                                    @if (Auth::user()->branch)
                                        <option value="{{ Auth::user()->branch->branchId }}" selected>
                                            {{ Auth::user()->branch->address }}
                                        </option>
                                    @else
                                        @foreach ($branch as $b)
                                            <option value="{{ $b->branchId }}">{{ $b->address }}</option>
                                        @endforeach
                                    @endif
                                </select>                                
                            </div>                                      
                        </div>
                        <div class="col-md-6">  
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" name="foto" class="form-control" id="foto">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password"  placeholder="Masukkan password">
                            </div>
                        </div>
                    </div>                
                </div>
                    <!-- Data Supervisor Section -->
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Petugas</h5>
                </div>
                <div class="card-body">
                    <!-- Nama Data Petugas Email -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" id="name"  placeholder="Masukkan nama user">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea name="address" id="address" cols="30" rows="5" class="form-control"></textarea>
                            </div> 
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="birthdate" class="form-control" id="birthdate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Nomor Telepon</label>
                                <input type="number"  name="telepon" class="form-control" id="telepon" placeholder="Masukkan nomor telepon">
                            </div>
                            <div class="mb-3">
                                <label for="hire_date" class="form-label">Tanggal karyawan mulai bekerja</label>
                                <input type="date" name="hire_date" class="form-control" id="hire_date">
                            </div>
                            <div class="mb-3">
                                <label for="salary" class="form-label">Gaji</label>
                                <input type="number" name="salary" class="form-control" id="salary">
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="0">Laki laki</option>
                                    <option value="1">Perempuan</option>
                                </select>
                            </div>            
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" onclick="tambahUser()">Tambah</button>
                </div>
                
                <!-- Tabel sementara -->
                <div class="col-md-12">
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Petugas</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle text-center" id="employeeTable">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 15%;">Foto</th>
                                            <th style="width: 15%;">Nama</th>
                                            <th style="width: 15%;">Email</th>
                                            <th style="width: 15%;">Password</th>
                                            <th style="width: 10%;">Role</th>
                                            <th style="width: 10%;">Nomor Telepon</th>
                                            <th style="width: 10%;">Alamat</th>
                                            <th style="width: 10%;">Tanggal lahir</th>
                                            <th style="width: 10%;">Tanggal Masuk</th>
                                            <th style="width: 10%;">Gaji</th>
                                            <th style="width: 10%;">Jenis Kelamin</th>
                                            <th style="width: 10%;">Cabang</th>
                                            <th style="width: 5%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <!-- Data user sementara -->
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
        let usersList = [];

        function tambahUser() {
            let email    = document.getElementById('email').value.trim();
            let role = document.getElementById('role').value.trim();
            let password = document.getElementById('password').value.trim();
            let name     = document.getElementById('name').value.trim();
            let telepon  = document.getElementById('telepon').value.trim();
            let foto     = document.getElementById('foto').files[0]; 
            let address  = document.getElementById('address').value.trim();
            let birthdate  = document.getElementById('birthdate').value.trim();
            let hire_date  = document.getElementById('hire_date').value.trim();
            let salary  = document.getElementById('salary').value.trim();
            let gender  = document.getElementById('gender').value.trim();
            let branch_id  = document.getElementById('branch_id').value.trim();

            if (email === '' || role === '' || password === '' || name === '' || telepon === '' || !foto || address === '' || birthdate === ''
                || hire_date === '' || salary === '' || gender === '' || branch_id === ''
            ) {
                alert("Semua data wajib diisi, termasuk foto!");
                return;
            }

            let newData = {
                id: Date.now(),
                email, role, password, name, telepon, foto, address, birthdate, hire_date, salary, gender, branch_id
            };

            usersList.push(newData);

            document.getElementById('foto').value = '';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('telepon').value = '';
            document.getElementById('address').value = '';
            document.getElementById('birthdate').value = '';
            document.getElementById('hire_date').value = '';
            document.getElementById('salary').value = '';
            document.getElementById('gender').value = '';
            document.getElementById('branch_id').value = '';
            
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#employeeTable tbody");
            tbody.innerHTML = '';

            usersList.forEach((item, index) => {
                let fotoPreview = item.foto ? URL.createObjectURL(item.foto) : '';

                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <img src="${fotoPreview}" alt="Foto" class="img-thumbnail" style="max-width: 100px; max-height: 100px;" />
                        </td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editUser(${item.id}, 'name', this.value)"></td>
                        <td><input type="email" class="form-control" value="${item.email}" onchange="editUser(${item.id}, 'email', this.value)"></td>
                        <td><input type="password" class="form-control" value="${item.password}" onchange="editUser(${item.id}, 'password', this.value)"></td>
                        <td>
                            <select class="form-control" onchange="editUser(${item.id}, 'role', this.value)">
                                <option value="petugas" ${item.role === 'petugas' ? 'selected' : ''}>Petugas</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control" value="${item.telepon}" onchange="editUser(${item.id}, 'telepon', this.value)"></td>
                        <td><input type="text" class="form-control" value="${item.address}" onchange="editUser(${item.id}, 'address', this.value)"></td>
                        <td><input type="date" class="form-control" value="${item.birthdate}" onchange="editUser(${item.id}, 'birthdate', this.value)"></td>
                        <td><input type="date" class="form-control" value="${item.hire_date}" onchange="editUser(${item.id}, 'hire_date', this.value)"></td>
                        <td><input type="number" class="form-control" value="${item.salary}" onchange="editUser(${item.id}, 'salary', this.value)"></td>
                        <td>
                            <select class="form-control" onchange="editUser(${item.id}, 'gender', this.value)">
                                <option value="0" ${item.gender === '0' ? 'selected' : ''}>Laki laki</option>
                                <option value="1" ${item.gender === '1' ? 'selected' : ''}>Perempuan</option>
                            </select>
                        </td>
                        <td>    
                            <select class="form-control" onchange="editUser(${item.id}, this.value)">
                                @foreach ($branch as $b)  
                                    <option value="{{ $b->branchId }}" ${item.branch_id == {{ $b->branchId }} ? 'selected' : ''}>
                                        {{ $b->branchName }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusUser(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editUser(id, field, newValue) {
            let user = usersList.find(item => item.id === id);
            if (user) {
                user[field] = newValue;
            }
        }

        function hapusUser(id) {
            usersList = usersList.filter(item => item.id !== id);
            renderTable();
        }

        function simpanSemua() {
            if (usersList.length === 0) {
                alert("Tidak ada data untuk disimpan!");
                return;
            }

            let requests = usersList.map(item => {
                let formData = new FormData();
                formData.append('email', item.email);
                formData.append('role', item.role);
                formData.append('password', item.password);
                formData.append('name', item.name);
                formData.append('telepon', item.telepon);
                formData.append('foto', item.foto); 
                formData.append('address', item.address); 
                formData.append('birthdate', item.birthdate); 
                formData.append('hire_date', item.hire_date); 
                formData.append('salary', item.salary); 
                formData.append('gender', item.gender); 
                formData.append('branch_id', item.branch_id); 

                return fetch("{{ url('people/employee/store') }}", {
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
                    console.error("Gagal menyimpan user:", error);
                });
            });

            Promise.all(requests).then(() => {
                usersList = [];
                renderTable();
                alert("Semua data berhasil disimpan!");
                window.location.href = "/people/employee/";
            });
        }

    </script>
@endpush
@endsection
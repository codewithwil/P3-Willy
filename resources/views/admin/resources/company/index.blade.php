@extends('admin.template.template')
@section('title', 'Company')

@section('content')
@push('css')
<!-- Remove Select2 CSS link -->
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Informasi Perusahaan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">Perusahaan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="/configuration/company/store" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <!-- Data Perusahaan Section -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Perusahaan</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nama Perusahaan and Email -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="companyName" class="form-label">Nama Perusahaan</label>
                                    <input type="text" name="name" class="form-control" id="companyName" value="{{ $company->name ?? ''}}" placeholder="Masukkan nama perusahaan">
                                </div>
                                <div class="mb-3">
                                    <label for="companyEmail" class="form-label">Email Perusahaan</label>
                                    <input type="email" name="email" class="form-control" id="companyEmail" value="{{ $company->email ?? ''}}" placeholder="Masukkan email perusahaan">
                                </div>
                                <div class="mb-3">
                                    <label for="companyPhone" class="form-label">Nomor Telepon Perusahaan</label>
                                    <input type="number" name="phone" class="form-control" id="companyPhone" value="{{ $company->phone ?? ''}}" placeholder="Masukkan nomor telepon">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="companyPhoto" class="form-label">Foto Perusahaan</label>
                                    <input type="file" name="image" class="mt-1 border border-gray-300 rounded w-full" accept="image/*" {{ $company ?? '' ? '' : 'required' }}>

                                    @error('image')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                    @if(isset($company->image))
                                    <p>Gambar lama:</p>
                                    <div class="mb-2">
                                        <img src="{{ asset($company->image) }}" alt="Existing Image" style="max-width: 400px" class=" object-cover rounded border border-gray-300">
                                    </div>
                                    @endif
                                    <div id="preview" class="mt-3 text-center">
                                        <img src="" id="previewImage" class="img-thumbnail d-none" alt="Preview Foto Perusahaan" style="max-width: 400px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Informasi Alamat Perusahaan Section -->
                    <div class="card-header bg-primary text-white mt-4">
                        <h5 class="mb-0">Informasi Alamat Perusahaan</h5>
                    </div>
                    <div class="card-body">
                        <!-- Alamat Perusahaan -->
                        <div class="col-md-12 mt-3">
                            <label for="companyAddress" class="form-label">Alamat Perusahaan</label>
                            <textarea class="form-control" name="address" id="companyAddress" placeholder="Masukkan alamat perusahaan" cols="30" rows="4">{{ $company->address ?? ''}}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
 document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('companyPhoto');
    const previewImage = document.getElementById('previewImage');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = '#e9ecef';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.backgroundColor = '#f8f9fa';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = '#f8f9fa';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files; 
            showPreview(files[0]); 
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            showPreview(fileInput.files[0]);
        }
    });

    function showPreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        // Hapus isi yang sudah ada di dropZone hanya jika perlu
        dropZone.innerHTML = ''; // Hanya mengosongkan area untuk gambar

        // Buat elemen gambar
        const img = document.createElement('img');
        img.src = e.target.result;
        img.classList.add('img-thumbnail');
        img.style.objectFit = 'cover'; // Membuat gambar menyesuaikan ukuran
        img.style.width = '100%'; // Memastikan gambar lebar penuh
        img.style.height = '100%'; // Memastikan gambar tinggi penuh
        img.style.borderRadius = '8px'; // Menambahkan sudut gambar yang rapi

        // Tambahkan gambar ke dalam dropZone
        dropZone.appendChild(img);
    };
    reader.readAsDataURL(file);
}

});

</script>

    
@endpush
@endsection

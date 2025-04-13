@extends('admin.template.template')
@section('title', 'edit Data Barang')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Barang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('transactions/commodities/update/'.$commodity->commoditiesId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Barang</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $commodity->name }}"  placeholder="Masukkan Nama Barang">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Kategori barang</label>
                                    <select name="category_id" class="form-control" id="buildings">
                                        <option value="">--- Pilih Kategori ---</option>
                                        @foreach ($category as $cat)  
                                        <option value="{{ $cat->categoryId }}" {{ $cat->categoryId == $commodity->category_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>   
                                <div class="mb-3">
                                    <label for="units" class="form-label">Pilih Unit</label>
                                    <select name="unit_id" class="form-control" id="units">
                                        <option value="">--- Pilih Unit ---</option>
                                        @foreach ($unit as $un)  
                                            <option value="{{ $un->unitId }}" {{ $un->unitId == $commodity->unit_id ? 'selected' : '' }}>
                                                {{ $un->abbreviation }}
                                            </option>
                                        @endforeach
                                    </select>         
                                </div>            
                                <div class="mb-3">
                                    <label for="ruangan" class="form-label">Pilih Ruangan</label>
                                    <select name="room_Id" class="form-control" id="ruangan">
                                        <option value="">--- Pilih Ruangan ---</option>
                                        @foreach ($rooms as $ro)  
                                            <option value="{{ $ro->roomId }}" {{ $ro->roomId == $commodity->room_Id ? 'selected' : '' }}>
                                                {{ $ro->roomName }}
                                            </option>
                                        @endforeach
                                    </select>         
                                </div>     
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto Barang</label>
                                    <input type="file" name="image" id="foto" class="mt-1 border border-gray-300 rounded w-full" accept="image/*">
                                    @error('image')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                    @if(isset($commodity->image))
                                    <p>Gambar lama:</p>
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$commodity->image) }}" alt="Existing Image" style="max-width: 200px" class=" object-cover rounded border border-gray-300">
                                    </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="merk" class="form-label">Merk</label>
                                    <input type="text" name="merk" class="form-control" id="merk" value="{{ $commodity->merk }}"  placeholder="Masukkan Merk Barang">
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Barang</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">--- Tipe Barang ---</option>
                                        <option value="1" {{ old('type', $commodity->type) == 1 ? 'selected' : '' }}>ALAT</option>
                                        <option value="2" {{ old('type', $commodity->type) == 2 ? 'selected' : '' }}>SPAREPART</option>
                                    </select>
                                </div>                                
                                <div class="mb-3">
                                    <label for="desc" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="desc" id="desc" placeholder="Masukkan Deskripsi Barang" cols="30" rows="4">{{ $commodity->desc }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Jumlah Awal Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qty" class="form-label">Jumlah</label>
                                    <input type="number"  name="initial_stock" value="{{ $commodity->stock->first() ? $commodity->stock->first()->quantity : '' }}" 
                                     class="form-control" id="qty"  
                                     placeholder="Masukkan Jumlah Awal Barang">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga Barang</label>
                                    <input type="number" min="1" name="price" class="form-control" id="price" value="{{ $commodity->price }}"  placeholder="Masukkan Harga Barang">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@extends('admin.template.template')
@section('title', 'tambah Data Servis Barang')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Servis Barang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Servis Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('transactions/outCommod/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Servis Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="outCode">Kode Barang Keluar</label>
                                    <input type="text" name="" class="form-control" id="outCode" value="{{ $outCode }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="commodity" class="form-label">Barang</label>
                                    <select name="commodity_id" id="commodity" class="form-control" onchange="console.log(this.value)">
                                        <option value="">--- Pilih Barang ---</option>
                                        @foreach ($commodity as $com)
                                            <option value="{{ $com->commoditiesId }}" 
                                                    data-name="{{ $com->name }}" 
                                                    data-category="{{ $com->category->name }}" 
                                                    data-image="{{ asset('storage/' . $com->image) }}">
                                                {{ $com->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="addCommodity" class="btn btn-success mb-3">Tambah Barang</button>
                                <div class="mb-3">
                                    <label for="dateOut">Tanggal Barang Keluar</label>
                                    <input type="date" name="dateOut" class="form-control" id="dateOut">
                                </div>
                                <div class="mb-3">
                                    <label for="type">Tipe Barang Keluar</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">--- Tipe Barang Keluar ---</option>
                                        <option value="0">PENJUALAN</option>
                                        <option value="1">PERBAIKAN</option>
                                        <option value="2">RUSAK</option>
                                        <option value="3">LAIN LAIN</option>
                                    </select>
                                </div>                            
                                <div class="mb-3">
                                    <label for="note" class="form-label">Catatan</label>
                                    <textarea class="form-control" name="note" id="note" placeholder="Masukkan Catatan *(tidak wajib)" cols="30" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Barang yang Dipilih</h5>
                                <ul id="selectedCommodities" class="list-group">
                                </ul>
                            </div>

                            <div id="salesInfo" class="card mt-3" style="display: none;">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Informasi Penjualan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="customerName" class="form-label">Nama Pelanggan</label>
                                        <input type="text" name="outDetails[customerName]" class="form-control" id="customerName" placeholder="Masukkan Nama Pelanggan">
                                    </div>  
                                    <div class="mb-3">
                                        <label for="customerPhone" class="form-label">Nomor Telepon Pelanggan</label>
                                        <input type="number" name="outDetails[customerPhone]" class="form-control" id="customerPhone" placeholder="Masukkan Nomor Telepon Pelanggan">
                                    </div>   
                                    <div class="mb-3">
                                        <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                                        <select name="outDetails[paymentMethod]" id="paymentMethod" class="form-control">
                                            <option value="">--- Pilih Metode Pembayaran ---</option>
                                            <option value="cash">Tunai</option>
                                            <option value="transfer">Transfer</option>
                                            <option value="credit">Kredit</option>
                                        </select>
                                    </div>                              
                                </div>
                            </div>

                            {{-- if select service  --}}
                            <div id="serviceInfo" class="card mt-3" style="display: none;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Informasi Toko Servis</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="serviceName" class="form-label">Nama Toko Service</label>
                                                <input type="text" name="outDetails[serviceName]" class="form-control" id="serviceName" placeholder="Masukkan Nama Toko Servis">
                                            </div>  
                                            <div class="mb-3">
                                                <label for="servicePhone" class="form-label">Nomor Telepon Toko Service</label>
                                                <input type="number" name="outDetails[servicePhone]" class="form-control" id="servicePhone" placeholder="Masukkan Nomor Telepon Toko Servis">
                                            </div>                              
                                        </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                                <label for="serviceAddress" class="form-label">Alamat Toko</label>
                                                <textarea class="form-control" name="outDetails[serviceAddress]" id="serviceAddress" placeholder="Masukkan Alamat Toko Servis" cols="30" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    const selectedCommodities = [];
    const commoditySelect = document.getElementById('commodity');
    const addCommodityButton = document.getElementById('addCommodity');
    const selectedCommoditiesList = document.getElementById('selectedCommodities');

    function renderSelectedCommodities() {
        selectedCommoditiesList.innerHTML = '';
        selectedCommodities.forEach((commodity, index) => {
            const { name, category, image, quantity } = commodity;

            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';

            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${image}" alt="${name}" style="width: 100px; height: 50px; object-fit: cover; margin-right: 10px;">
                    <span>${name}</span>
                    <span class="ms-3">${category}</span>
                </div>
                <input type="number" class="form-control w-25" value="${quantity}" min="1" data-index="${index}" onchange="updateQuantity(event)">
            `;

            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.textContent = 'Hapus';
            removeButton.onclick = () => {
                selectedCommodities.splice(index, 1);
                renderSelectedCommodities();
            };

            listItem.appendChild(removeButton);
            selectedCommoditiesList.appendChild(listItem);
        });
    }

    function updateQuantity(event) {
        const index = event.target.getAttribute('data-index');
        const newQuantity = event.target.value;
        selectedCommodities[index].quantity = newQuantity;
    }

    addCommodityButton.addEventListener('click', () => {
        const selectedValue = commoditySelect.value;
        const selectedName = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-name');
        const selectedCategory = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-category');
        const selectedImage = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-image');

        if (!selectedValue) {
            alert('Pilih barang terlebih dahulu!');
            return;
        }

        if (selectedCommodities.some(item => item.id === selectedValue)) {
            alert('Barang sudah dipilih!');
            return;
        }

        selectedCommodities.push({ 
            id: selectedValue, 
            name: selectedName, 
            category: selectedCategory, 
            image: selectedImage, 
            quantity: 1 
        });
        renderSelectedCommodities();
    });

    document.querySelector('form').addEventListener('submit', (e) => {
    if (selectedCommodities.length === 0) {
        e.preventDefault();
        alert('Pilih setidaknya satu barang sebelum menyimpan!');
        return;
    }

    const commodityIds = selectedCommodities.map(item => item.id);
    const quantities = selectedCommodities.map(item => item.quantity);

    const commodityIdsInput = document.createElement('input');
    commodityIdsInput.type = 'hidden';
    commodityIdsInput.name = 'commodity_id[]';  
    commodityIdsInput.value = JSON.stringify(commodityIds);
    e.target.appendChild(commodityIdsInput);

    const quantitiesInput = document.createElement('input');
    quantitiesInput.type = 'hidden';
    quantitiesInput.name = 'quantity[]';  
    quantitiesInput.value = JSON.stringify(quantities);
    e.target.appendChild(quantitiesInput);
});

</script>

<script>
  $(document).ready(function(){
        $('#type').change(function(){
            let selectedValue = $(this).val();
            
            if (selectedValue === "0") { 
                $('#salesInfo').slideDown();
                $('#serviceInfo').slideUp();
            } else if (selectedValue === "1") { 
                $('#serviceInfo').slideDown();
                $('#salesInfo').slideUp();
            } else { 
                $('#salesInfo, #serviceInfo').slideUp();
            }
        });
    });
</script>
@endpush


@endsection

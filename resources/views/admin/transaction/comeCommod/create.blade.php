@extends('admin.template.template')
@section('title', 'tambah Data Pembelian Barang')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Pembelian Barang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Pembelian Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('transactions/comeCommod/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Pembelian Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="comComeCode">Kode Pembelian Barang</label>
                                    <input type="text" name="" class="form-control" id="comComeCode" value="{{ $comComeCode }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="commodity" class="form-label">Barang</label>
                                    <select name="commodity_id" id="commodity" class="form-control" onchange="console.log(this.value)">
                                        <option value="">--- Pilih Barang ---</option>
                                        @foreach ($commodity as $com)
                                            <option value="{{ $com->commoditiesId }}" 
                                                    data-name="{{ $com->name }}" 
                                                    data-price="{{ $com->price }}" 
                                                    data-category="{{ $com->category->name }}" 
                                                    data-image="{{ asset('storage/' . $com->image) }}">
                                                {{ $com->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="addCommodity" class="btn btn-success mb-3">Tambah Barang</button>
                                <div class="mb-3">
                                    <label for="date">Tanggal Barang Masuk</label>
                                    <input type="date" name="date" class="form-control" id="date" placeholder="Masukkan Tanggal Barang Masuk">
                                </div>
                                <div class="mb-3">
                                    <label for="supplier">Supplier</label>
                                    <select name="supplier_id" id="supplier" class="form-control">
                                        <option value="">--- Pilih Supplier ---</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->supplierId }}">{{ $sup->name }}</option>                                            
                                        @endforeach
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
                            
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Informasi Harga</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payment" class="form-label">Tipe Pembayaran</label>
                                            <select name="payment" id="payment" class="form-control">
                                                <option value="">--- Pilih Tipe Pembayaran---</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Transfer</option>
                                                <option value="3">Kredit</option>
                                                <option value="4">Debet</option>
                                            </select>
                                        </div>                          
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="total" class="form-label">Total</label>
                                            <input type="number" class="form-control" name="total" id="total" readonly>
                                        </div>
                                    </div>
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

@push('js')
<script>
   const selectedCommodities = [];
const commoditySelect = document.getElementById('commodity');
const addCommodityButton = document.getElementById('addCommodity');
const selectedCommoditiesList = document.getElementById('selectedCommodities');
const totalInput = document.getElementById('total');

function renderSelectedCommodities() {
    selectedCommoditiesList.innerHTML = '';
    let total = 0;  

    selectedCommodities.forEach((commodity, index) => {
        const { name, category, image, quantity, price } = commodity;

        const listItem = document.createElement('li');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';

        listItem.innerHTML = `
            <div class="d-flex align-items-center">
                <img src="${image}" alt="${name}" style="width: 100px; height: 50px; object-fit: cover; margin-right: 10px;">
                <span>${name}</span>
                <span class="ms-3">${category}</span>
                <span class="ms-3">Rp ${price.toLocaleString()}</span>
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

        // Update total harga (tidak pakai pemisah ribuan)
        total += price * quantity;
    });

    // Update field total harga tanpa pemisah ribuan
    totalInput.value = total;  // Menggunakan angka mentah tanpa format
}

function updateQuantity(event) {
    const index = event.target.getAttribute('data-index');
    const newQuantity = event.target.value;
    selectedCommodities[index].quantity = newQuantity;
    renderSelectedCommodities(); // Re-render untuk menghitung ulang total harga
}

addCommodityButton.addEventListener('click', () => {
    const selectedValue = commoditySelect.value;
    const selectedName = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-name');
    const selectedCategory = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-category');
    const selectedImage = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-image');
    const selectedPrice = parseFloat(commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-price'));

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
        price: selectedPrice, 
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

    // Tambahkan input hidden untuk commodity_id[] 
    const commodityIdsInput = document.createElement('input');
    commodityIdsInput.type = 'hidden';
    commodityIdsInput.name = 'commodity_id[]';  
    commodityIdsInput.value = JSON.stringify(commodityIds);
    e.target.appendChild(commodityIdsInput);

    // Tambahkan input hidden untuk quantity[] 
    const quantitiesInput = document.createElement('input');
    quantitiesInput.type = 'hidden';
    quantitiesInput.name = 'quantity[]';  
    quantitiesInput.value = JSON.stringify(quantities);
    e.target.appendChild(quantitiesInput);
});


</script>
@endpush


@endsection

@extends('admin.template.template')
@section('title', 'tambah Data Servis Kendaraan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Servis Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Servis Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('transactions/serviceV/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Servis Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="servCode">Kode Servis</label>
                                    <input type="text" name="" class="form-control" id="servCode" value="{{ $servCode }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="brandM" class="form-label">Merk Kendaraan</label>
                                    <select name="brandMoto_Id" class="form-control" id="brandM">
                                        <option value="">--- Pilih Merk Kendaraan ---</option>
                                        @foreach ($brand as $b)  
                                            <option value="{{ $b->brandMotorId }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="brandM" class="form-label">Teknisi</label>
                                    <select name="Empshift_id" class="form-control" id="brandM">
                                        <option value="">--- Pilih Teknisi ---</option>
                                        @foreach ($empShift as $e)  
                                            <option value="{{ $e->empShiftId }}">{{ $e->users->name }}</option>
                                        @endforeach
                                    </select>
                                </div>     
                                <div class="mb-3">
                                    <label for="platNo">Plat Nomer Kendaraan</label>
                                    <input type="text" name="platNo" class="form-control" placeholder="masukan plat kendaraan" id="platNo">
                                </div>
                                <div class="mb-3">
                                    <label for="customers">Nama Pelanggan</label>
                                    <input type="text" name="customers" class="form-control" placeholder="masukan nama pelanggan" id="customers">
                                </div>
                                <div class="mb-3">
                                    <label for="phoneCustomers">Nomor Telepon Pelanggan</label>
                                    <input type="number" min="0" name="phoneCustomers" class="form-control" placeholder="masukan nomor Telepon Pelanggan" id="phoneCustomers">
                                </div>   
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="typeVehicle">Jenis Kendaraan</label>
                                    <select name="typeVehicle" id="typeVehicle" class="form-control">
                                        <option value="">--- Jenis Kendaraan ---</option>
                                        <option value="1">Motor</option>
                                        <option value="2">Mobil</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Kendaraan</label>
                                    <select name="typeV_id" class="form-control" id="type">
                                        <option value="">--- Pilih Merk Kendaraan ---</option>
                                        @foreach ($type as $t)  
                                            <option value="{{ $t->TypeVId }}">{{ $t->typeText }} - {{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="typeServ">Tipe Servis</label>
                                    <select name="typeServ" id="typeServ" class="form-control">
                                        <option value="">--- Tipe Servis ---</option>
                                        <option value="1">Ringan</option>
                                        <option value="2">Besar</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="dateService">Tanggal Servis</label>
                                    <input type="date" name="dateService" class="form-control" id="dateService">
                                </div>
                                <div class="mb-3">
                                    <label for="endService">Tanggal Selesai Servis</label>
                                    <input type="date" name="endService" class="form-control" id="endService">
                                </div>
                                <div class="mb-3">
                                    <label for="desc" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="desc" id="desc" placeholder="Masukkan Deskripsi *(tidak wajib)" cols="30" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Sparepart Yang Digunakan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="commodity" class="form-label">Sparepart</label>
                                            <select name="commodity_id" id="commodity" class="form-control" onchange="console.log(this.value)">
                                                <option value="">--- Pilih Sparepart ---</option>
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h5>Sparepart yang Dipilih</h5>
                                            <ul id="selectedCommodities" class="list-group">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Kalkulasi Harga</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ServPrice" class="form-label">Biaya Jasa Servis</label>
                                            <input type="number" min="1" placeholder="masukan biaya jasa servis" name="ServPrice" id="ServPrice" class="form-control">
                                        </div>    
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Diskon Servis</label>
                                            <input type="number" min="1" placeholder="masukan diskon servis" name="discount" id="discount" class="form-control">
                                        </div>   
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="total" class="form-label">Total</label>
                                            <input type="number" placeholder="0" name="total" id="total" class="form-control" readonly>
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
    const selectedCommodities     = [];
    const commoditySelect         = document.getElementById('commodity');
    const addCommodityButton      = document.getElementById('addCommodity');
    const selectedCommoditiesList = document.getElementById('selectedCommodities');
    const servPriceInput          = document.getElementById('ServPrice');
    const discountInput           = document.getElementById('discount');
    const totalInput              = document.getElementById('total');

    function renderSelectedCommodities() {
        selectedCommoditiesList.innerHTML = '';
        selectedCommodities.forEach((commodity, index) => {
            const { name, category, image, quantity, price } = commodity;

            const listItem     = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';

            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${image}" alt="${name}" style="width: 100px; height: 50px; object-fit: cover; margin-right: 10px;">
                    <span>${name}</span>
                    <span class="ms-3">${category}</span>
                </div>
                <input type="number" class="form-control w-25" value="${quantity}" min="1" data-index="${index}" onchange="updateQuantity(event)">
                <span class="price ms-3">${(price * quantity).toFixed(2)}</span>
            `;

            const removeButton       = document.createElement('button');
            removeButton.className   = 'btn btn-danger btn-sm';
            removeButton.textContent = 'Hapus';
            removeButton.onclick     = () => {
                selectedCommodities.splice(index, 1);
                renderSelectedCommodities();
                updateTotal();
            };

            listItem.appendChild(removeButton);
            selectedCommoditiesList.appendChild(listItem);
        });
    }

    function updateQuantity(event) {
        const index = event.target.getAttribute('data-index');
        const newQuantity = event.target.value;
        selectedCommodities[index].quantity = newQuantity;
        renderSelectedCommodities();
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        selectedCommodities.forEach(commodity => {
            total += commodity.price * commodity.quantity;
        });

        const serviceFee = parseFloat(servPriceInput.value) || 0;
        const discount   = parseFloat(discountInput.value) || 0;
        const discountAmmount = (total * discount) / 100;

        total = total + serviceFee - discountAmmount;

        totalInput.value = total.toFixed(2);
    }

    addCommodityButton.addEventListener('click', () => {
        const selectedValue    = commoditySelect.value;
        const selectedName     = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-name');
        const selectedCategory = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-category');
        const selectedImage    = commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-image');
        const selectedPrice    = parseFloat(commoditySelect.options[commoditySelect.selectedIndex]?.getAttribute('data-price'));

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
        updateTotal();
    });

    servPriceInput.addEventListener('input', updateTotal);
    discountInput.addEventListener('input', updateTotal);

    document.querySelector('form').addEventListener('submit', (e) => {
        if (selectedCommodities.length === 0) {
            e.preventDefault();
            alert('Pilih setidaknya satu barang sebelum menyimpan!');
            return;
        }

        const commodityIds = selectedCommodities.map(item => item.id);
        const quantities   = selectedCommodities.map(item => item.quantity);

        const commodityIdsInput = document.createElement('input');
        commodityIdsInput.type  = 'hidden';
        commodityIdsInput.name  = 'commodity_id[]';  
        commodityIdsInput.value = JSON.stringify(commodityIds);
        e.target.appendChild(commodityIdsInput);

        const quantitiesInput = document.createElement('input');
        quantitiesInput.type  = 'hidden';
        quantitiesInput.name  = 'quantity[]';  
        quantitiesInput.value = JSON.stringify(quantities);
        e.target.appendChild(quantitiesInput);
});

</script>

@endpush


@endsection

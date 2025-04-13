@extends('admin.template.template')
@section('title', 'edit Data Peminjaman')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Peminjaman</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Peminjaman</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('transactions/loanings/update/'.$loanings->loaningId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="itemCode">Kode Peminjaman</label>
                                    <input type="text" name="" class="form-control" id="itemCode" value="{{ $loanings->itemCode }}" disabled>
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
                                    <label for="loanDate">Tanggal Peminjaman</label>
                                    <input type="date" name="loanDate" class="form-control" id="loanDate" value="{{ $loanings->loanDate }}" placeholder="Masukkan Tanggal Peminjaman">
                                </div>
                                <div class="mb-3">
                                    <label for="note" class="form-label">Catatan</label>
                                    <textarea class="form-control" name="note" id="note" placeholder="Masukkan Catatan *(tidak wajib)" cols="30" rows="4">{{ $loanings->note }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Barang yang Dipilih</h5>
                                <ul id="selectedCommodities" class="list-group">
                                    @foreach ($loanings->commodities as $commodity)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/'.$commodity->image) }}" alt="{{ $commodity->name }}" style="width: 100px; height: 50px; object-fit: cover; margin-right: 10px;">
                                            <span>{{ $commodity->name }}</span>
                                            <span class="ms-3">{{ $commodity->category->name }}</span>
                                        </div>
                                        <input type="number" class="form-control w-25" value="{{ $commodity->pivot->quantity }}" min="1" data-id="{{ $commodity->commoditiesId }}" onchange="updateQuantity(event)">
                                        <button class="btn btn-danger btn-sm" onclick="removeCommodity({{ $commodity->id }})">Hapus</button>
                                    </li>
                                @endforeach
                                </ul>
                            
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

    function removeCommodity(commodityId) {
        const index = selectedCommodities.findIndex(commodity => commodity.id === commodityId);
        if (index !== -1) {
            selectedCommodities.splice(index, 1);
            renderSelectedCommodities();
        }
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
        const existingCommodities = [...document.querySelectorAll('ul#selectedCommodities li')].map(li => {
            const id = li.querySelector('input[type="number"]').getAttribute('data-id');
            const quantity = li.querySelector('input[type="number"]').value;
            return { id, quantity };
        });

        const allCommodities = [...existingCommodities, ...selectedCommodities];
        if (allCommodities.length === 0) {
            e.preventDefault();
            alert('Pilih setidaknya satu barang sebelum menyimpan!');
            return;
        }

        const commodityIds = allCommodities.map(item => item.id);
        const quantities = allCommodities.map(item => item.quantity);


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
@endpush


@endsection

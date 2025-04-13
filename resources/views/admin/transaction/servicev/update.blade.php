@extends('admin.template.template')
@section('title', 'update Data Servis Kendaraan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Update Data Servis Kendaraan</h3></div>
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
            <form class="row g-3" action="{{ url('transactions/serviceV/update/'.$services->serVId) }}" method="POST" enctype="multipart/form-data">
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
                                    <input type="text" name="" class="form-control" id="servCode" value="{{ $services->servCode }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="brandM" class="form-label">Merk Kendaraan</label>
                                    <select name="brandMoto_Id" class="form-control" id="brandM">
                                        <option value="">--- Pilih Merk Kendaraan ---</option>
                                        @foreach ($brand as $b)  
                                            <option value="{{ $b->brandMotorId }}"
                                                {{ $b->brandMotorId == $services->brandMoto_Id ? 'selected' : '' }}>
                                                {{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="brandM" class="form-label">Teknisi</label>
                                    <select name="Empshift_id" class="form-control" id="brandM">
                                        <option value="">--- Pilih Teknisi ---</option>
                                        @foreach ($empShift as $e)  
                                        <option value="{{ $e->empShiftId }}"
                                            @if(optional($e->users)->id == optional($services->empShift)->user_id) 
                                                selected 
                                            @endif>
                                            {{ $e->users->name }}
                                        </option>
                                    @endforeach
                                    
                                    </select>
                                </div>     
                                <div class="mb-3">
                                    <label for="platNo">Plat Nomer Kendaraan</label>
                                    <input type="text" name="platNo" class="form-control" value="{{$services->platNo}}" placeholder="masukan plat kendaraan" id="platNo">
                                </div>
                                <div class="mb-3">
                                    <label for="customers">Nama Pelanggan</label>
                                    <input type="text" name="customers" class="form-control" value="{{$services->customers}}" placeholder="masukan nama pelanggan" id="customers">
                                </div>
                                <div class="mb-3">
                                    <label for="phoneCustomers">Nomor Telepon Pelanggan</label>
                                    <input type="number" min="0" name="phoneCustomers" class="form-control" value="{{$services->phoneCustomers}}" placeholder="masukan nomor Telepon Pelanggan" id="phoneCustomers">
                                </div>   
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="typeVehicle">Jenis Kendaraan</label>
                                    <select name="typeVehicle" id="typeVehicle" class="form-control">
                                        <option value="">--- Jenis Kendaraan ---</option>
                                        <option value="1" {{ old('typeVehicle', $services->typeVehicle) == 1 ? 'selected' : '' }}>Motor</option>
                                        <option value="2" {{ old('typeVehicle', $services->typeVehicle) == 2 ? 'selected' : '' }}>Mobil</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Kendaraan</label>
                                    <select name="typeV_id" class="form-control" id="type">
                                        <option value="">--- Pilih Merk Kendaraan ---</option>
                                        @foreach ($type as $t)  
                                            <option value="{{ $t->TypeVId }}"
                                                {{ $b->typeVehicle == $services->typeV_id ? 'selected' : '' }}>
                                                {{ $t->typeText }} - {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="typeServ">Tipe Servis</label>
                                    <select name="typeServ" id="typeServ" class="form-control">
                                        <option value="">--- Tipe Servis ---</option>
                                        <option value="1"  {{ old('typeServ', $services->typeServ) == 1 ? 'selected' : '' }}>Ringan</option>
                                        <option value="2"  {{ old('typeServ', $services->typeServ) == 2 ? 'selected' : '' }}>Besar</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="dateService">Tanggal Servis</label>
                                    <input type="date" name="dateService" class="form-control" value="{{$services->dateService}}" id="dateService">
                                </div>
                                <div class="mb-3">
                                    <label for="endService">Tanggal Selesai Servis</label>
                                    <input type="date" name="endService" class="form-control" value="{{$services->endService}}" id="endService">
                                </div>
                                <div class="mb-3">
                                    <label for="desc" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="desc" id="desc" placeholder="Masukkan Deskripsi *(tidak wajib)" cols="30" rows="4">{{$services->desc}}</textarea>
                                </div>
                            </div>

                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Sparepart Yang Digunakan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
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
                                        <button type="button" id="addCommodity" class="btn btn-success mb-3">Update Barang</button>                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h5>Barang yang Dipilih</h5>
                                            <ul id="selectedCommodities" class="list-group">
                                                @foreach ($services->commodities as $commodity)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('storage/'.$commodity->image) }}" alt="{{ $commodity->name }}" style="width: 100px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                        <span>{{ $commodity->name }}</span>
                                                        <span class="ms-3">{{ $commodity->category->name }}</span>
                                                        <span class="ms-3 price">{{ $commodity->price }}</span>
                                                    </div>
                                                    <input type="number" class="form-control w-25" value="{{ $commodity->pivot->quantity }}" min="1" data-id="{{ $commodity->commoditiesId }}" onchange="updateQuantity(event)">
                                                    <button class="btn btn-danger btn-sm" onclick="removeCommodity({{ $commodity->id }})">Hapus</button>
                                                </li>
                                                @endforeach
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
                                            <input type="number" min="1" placeholder="masukan biaya jasa servis" name="ServPrice" id="ServPrice" value="{{$services->ServPrice}}" class="form-control">
                                        </div>    
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Diskon Servis</label>
                                            <input type="number" min="1" placeholder="masukan diskon servis" name="discount" id="discount" value="{{ intval($services->discount) }}" class="form-control">
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
  
    function calculateTotal() {
            let total = 0;

            // Hitung total dari daftar barang yang sudah dipilih
            selectedCommodities.forEach(commodity => {
                total += parseFloat(commodity.price) * parseInt(commodity.quantity);
            });

            // Tambahkan total dari barang yang sudah ada di dalam layanan
            document.querySelectorAll('#selectedCommodities li').forEach(li => {
                const price = parseFloat(li.querySelector('.price').textContent);
                const quantity = parseInt(li.querySelector('input[type="number"]').value);
                total += price * quantity;
            });

            // Ambil nilai biaya servis
            const servPrice = parseFloat(document.getElementById('ServPrice').value) || 0;
            total += servPrice;

            // Ambil nilai diskon
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const discountAmmount = (total * discount) / 100;
            total -= discountAmmount;

            // Pastikan total tidak negatif
            total = Math.max(total, 0);

            // Tampilkan total di input total
            document.getElementById('total').value = total.toFixed(2);
    }

        document.getElementById('ServPrice').addEventListener('input', calculateTotal);
        document.getElementById('discount').addEventListener('input', calculateTotal);

        // Perbarui fungsi updateQuantity agar menghitung ulang total
        function updateQuantity(event) {
            const index = event.target.getAttribute('data-index');
            selectedCommodities[index].quantity = event.target.value;
            calculateTotal();
        }

        // Pastikan total dihitung saat pertama kali halaman dimuat
        document.addEventListener('DOMContentLoaded', calculateTotal);
  
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

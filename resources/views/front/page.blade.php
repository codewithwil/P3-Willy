@extends('front.layout.template')
@section('content')

<!-- Hero -->
<section class="py-5 bg-light text-center">
  <div class="container">
    <h1 class="display-5 fw-bold">Layanan Laundry Cepat & Bersih</h1>
    <p class="lead">Cuci baju jadi lebih praktis. Tinggal pesan, kami jemput dan antar!</p>
    <a href="#form" class="btn btn-primary btn-lg mt-3">Pesan Sekarang</a>
  </div>
</section>

<!-- Kenapa Pilih Kami -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-4">Kenapa Pilih Kami?</h2>
    <div class="row text-center">
      <div class="col-md-4">
        <h5>Cepat & Tepat Waktu</h5>
        <p>Estimasi selesai hanya 1-2 hari kerja.</p>
      </div>
      <div class="col-md-4">
        <h5>Harga Terjangkau</h5>
        <p>Mulai dari Rp7.000/kg, sudah termasuk setrika!</p>
      </div>
      <div class="col-md-4">
        <h5>Gratis Antar-Jemput</h5>
        <p>Layanan gratis jemput & antar ke rumah.</p>
      </div>
    </div>
  </div>
</section>

<!-- Formulir Pemesanan -->
<section class="py-5 bg-light" id="form">
  <div class="container">
    <h2 class="text-center mb-4">Formulir Pemesanan</h2>
    {{-- <form method="POST" action="{{ route('order.submit') }}"> --}}
      @csrf
      <div class="row g-4">

        <!-- Cabang -->
        <div class="col-12">
          <label for="cabang" class="form-label fw-semibold">
            <i class="bi bi-geo-alt-fill me-2"></i>Cabang Terdekat
          </label>
          <select class="form-select shadow-sm" id="cabang" name="cabang" required>
            <option selected disabled>Pilih cabang...</option>
            @foreach ($branch as $b)
              <option value="{{ $b->branchId }}">{{ $b->address }}</option>
            @endforeach
          </select>
          <small id="cabang-status" class="text-muted d-block mt-1">Mendeteksi lokasi...</small>
        </div>

        <!-- Nama -->
        <div class="col-md-6">
          <label for="nama" class="form-label fw-semibold">
            <i class="bi bi-person-fill me-2"></i>Nama Lengkap
          </label>
          <input type="text" class="form-control shadow-sm" id="nama" name="nama" placeholder="Nama Anda"
            value="{{ Auth::user()->customer->name }}" required>
        </div>

        <!-- Telepon -->
        <div class="col-md-6">
          <label for="telepon" class="form-label fw-semibold">
            <i class="bi bi-telephone-fill me-2"></i>Nomor WhatsApp
          </label>
          <input type="tel" class="form-control shadow-sm" id="telepon" name="telepon" placeholder="08xxxx"
            value="{{ Auth::user()->customer->telepon }}" required>
        </div>

        <!-- Alamat -->
        <div class="col-12">
          <label for="alamat" class="form-label fw-semibold">
            <i class="bi bi-house-door-fill me-2"></i>Alamat Lengkap
          </label>
          <textarea class="form-control shadow-sm" id="alamat" name="alamat" rows="2"
            placeholder="Contoh: Jl. Soekarno Hatta No.123, Bandung" required>{{ Auth::user()->customer->address }}</textarea>
        </div>

        <!-- Jenis Layanan -->
        <div class="col-md-6">
          <label for="layanan" class="form-label fw-semibold">
            <i class="bi bi-basket-fill me-2"></i>Jenis Layanan
          </label>
          <select class="form-select shadow-sm" id="layanan" name="layanan" required>
            <option value="">--- Pilih layanan ---</option>
            @foreach ($service as $s)
              <option value="{{ $s->serviceId }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Estimasi Berat -->
        <div class="col-md-6">
          <label for="minQuantity" class="form-label fw-semibold">
            <i class="bi bi-box2-fill me-2"></i>Jumlah Minimal 
          </label>
          <input type="text" class="form-control shadow-sm" id="minQuantity" name="minQuantity"
           readonly>
        </div>

        <div class="col-md-12">
          <label for="berat" class="form-label fw-semibold">
            <i class="bi bi-box2-fill me-2"></i>Estimasi Berat / Potongan
          </label>
          <input type="text" class="form-control shadow-sm" id="berat" name="berat"
            placeholder="Contoh: 5 Kg atau 10 potong" required>
        </div>

        <!-- Catatan -->
        <div class="col-12">
          <label for="catatan" class="form-label fw-semibold">
            <i class="bi bi-sticky-fill me-2"></i>Catatan Tambahan
          </label>
          <textarea class="form-control shadow-sm" id="catatan" name="catatan" rows="2"
            placeholder="(Opsional) Misal: Tolong jemput jam 10 pagi"></textarea>
        </div>


        <div class="col-12 text-center">
          <button type="button" class="btn btn-primary btn-lg px-5 shadow-sm mt-4" id="btnLanjut">
            <i class="bi bi-send-fill me-2"></i>Lanjut
          </button>
        </div>

<!-- Modal Estimasi -->
<div class="modal fade" id="modalEstimasi" tabindex="-1" aria-labelledby="estimasiLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="estimasiLabel">Estimasi Biaya</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p><strong>Layanan:</strong> <span id="previewLayanan"></span></p>
        <p><strong>Harga per</strong> <span id="previewUnit"></span>: <span id="previewHarga"></span></p>
        <p><strong>Estimasi Berat:</strong> <span id="previewBerat"></span></p>
        <div id="promoList" class="mb-3">
          <strong>Diskon:</strong>
          <ul class="mb-0 ps-3" style="font-size: 0.95rem;"></ul>
        </div>
        
        <div class="mb-3">
          <label for="deliveryOption" class="form-label fw-semibold">
            <i class="bi bi-truck me-2"></i>Metode Pengantaran
          </label>
          <select class="form-select" id="deliveryOption">
            <option value="1">Antar-Jemput</option>
            <option value="2">Saya Akan Antar Sendiri</option>
          </select>
          <small class="text-muted">Gratis ongkir hingga 1 km. Lebih dari itu, Rp5.000/km.</small>
        </div>
        <p id="ongkirWrapper" class="mb-0" style="display: none;">
          <strong>Biaya Pengantaran:</strong> <span id="previewOngkir">-</span>
        </p>
        
        <div class="mb-3">
          <label for="paymentMethod" class="form-label fw-semibold">
            <i class="bi bi-credit-card-fill me-2"></i>Metode Pembayaran
          </label>
          <select class="form-select" id="paymentMethod" name="paymentMethod" required>
            <option value="1">Bayar Tunai (Cash)</option>
            <option value="2">Bayar Dari Saldo Akun</option>
          </select>        
        </div>
        <hr>
        <p class="fs-5"><strong>Total Estimasi:</strong> <span id="previewTotal" class="text-primary"></span></p>
      </div>

      <div class="modal-footer">
        <form id="orderForm" method="POST" action="">
          @csrf
          <input type="hidden" name="customerId" value="{{Auth::user()->customer->customerId}}">
          <input type="hidden" name="branchId" id="branchId">
          <input type="hidden" name="serviceId" id="serviceId">          
          <input type="hidden" name="berat" value="">
          <input type="hidden" name="catatan" value="">
          <input type="hidden" name="deliveryOption" value="">
          <input type="hidden" name="ongkir" value="">
          <input type="hidden" name="paymentMethod" value="">          
          <button type="submit" class="btn btn-success">Konfirmasi & Pesan</button>
        </form>
      </div>
    </div>
  </div>
</div>

      </div>
    </form>
  </div>
</section>

@push('js')
<script>
  let cabangSelect; 
  let availableServices = [];

  document.addEventListener('DOMContentLoaded', function () {
    cabangSelect = document.getElementById('cabang');
    const layananSelect = document.getElementById('layanan');
    const cabangStatus = document.getElementById('cabang-status');
    const minQuantityInput = document.getElementById('minQuantity');

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, handleLocationError);
    } else {
      cabangStatus.innerText = "Browser tidak mendukung geolocation.";
    }

    cabangSelect.addEventListener('change', function () {
      const selectedBranchId = cabangSelect.value;
      document.getElementById('branchId').value = selectedBranchId; 

      fetchServicesByBranch(selectedBranchId, true);
    });

    layananSelect.addEventListener('change', function () {
    const selectedServiceId = layananSelect.value;
    document.getElementById('serviceId').value = selectedServiceId; 

    const selectedService = availableServices.find(service => service.serviceId == selectedServiceId);

    if (selectedService) {
      minQuantityInput.value = `${selectedService.minQuantity} ${selectedService.unitType}`;
    } else {
      minQuantityInput.value = '';
    }
  });


    function showPosition(position) {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;

      fetch('/setting/branch/nearest-branch', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
          latitude: userLat,
          longitude: userLng
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.branch) {
          const branchId = data.branch.branchId;
          document.getElementById('branchId').value = branchId;
          const distance = data.distance_km;

          for (let i = 0; i < cabangSelect.options.length; i++) {
            const option = cabangSelect.options[i];
            if (parseInt(option.value) === parseInt(branchId)) {
              cabangSelect.selectedIndex = i;

              let cleanText = option.text.replace(/\s\(.+\)/, '');
              option.text = `${cleanText} (${distance.toFixed(2)} km) (Rekomendasi)`;

              cabangStatus.innerText = `Cabang "${cleanText}" dipilih otomatis berdasarkan lokasi Anda.`;

              fetchServicesByBranch(branchId);
              break;
            }
          }
        } else {
          cabangStatus.innerText = "Cabang terdekat tidak ditemukan.";
        }
      })
      .catch(() => {
        cabangStatus.innerText = "Terjadi kesalahan saat mengambil data cabang.";
      });
    }

    function fetchServicesByBranch(branchId, manualSelect = false) {
  fetch('/front/data/getBranch', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
    },
    body: JSON.stringify({ branch_id: branchId })
  })
  .then(response => response.json())
  .then(data => {
    currentPromo = {
      cabang: (data.promo?.cabang || []).filter(p => parseInt(p.branch_id) === parseInt(branchId)),
      member: data.promo?.member || []
    };

    layananSelect.innerHTML = '<option value="">--- Pilih layanan ---</option>';
    availableServices = data.services || [];

    if (availableServices.length > 0) {
      availableServices.forEach(service => {
        const option = document.createElement('option');
        option.value = service.serviceId;
        option.text = service.name;
        layananSelect.appendChild(option);
      });
    } else {
      const option = document.createElement('option');
      option.value = '';
      option.text = 'Tidak ada layanan tersedia';
      layananSelect.appendChild(option);
    }

    minQuantityInput.value = '';

    if (manualSelect && navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      fetch('/setting/branch/distance-to', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
          latitude: position.coords.latitude,
          longitude: position.coords.longitude,
          branch_id: branchId
        })
      })
      .then(response => response.json())
      .then(locData => {
        const distance = locData.distance_km;
        for (let i = 0; i < cabangSelect.options.length; i++) {
          const option = cabangSelect.options[i];
          if (parseInt(option.value) === parseInt(branchId)) {
            let cleanText = option.text.replace(/\s\(.+\)/, '');
            option.text = `${cleanText} (${distance.toFixed(2)} km)`;
          }
        }
      });
    });
  }


      })
      .catch(() => {
        const option = document.createElement('option');
        option.value = '';
        option.text = 'Gagal memuat layanan';
        layananSelect.appendChild(option);
      });
    }

    function handleLocationError(error) {
      cabangStatus.innerText = "Gagal mendapatkan lokasi. Aktifkan izin lokasi di browser.";
    }
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const btnLanjut = document.getElementById('btnLanjut');
      const layananSelect = document.getElementById('layanan');
      const beratInput = document.getElementById('berat');
      const form = document.getElementById('orderForm');

      const layananText = document.getElementById('previewLayanan');
      const unitText = document.getElementById('previewUnit');
      const hargaText = document.getElementById('previewHarga');
      const beratText = document.getElementById('previewBerat');
      const totalText = document.getElementById('previewTotal');
      const promoList = document.querySelector('#promoList ul');


      btnLanjut.addEventListener('click', function () {
  const selectedId = layananSelect.value;
  const selectedService = availableServices.find(s => s.serviceId == selectedId);

  if (!selectedService) return alert("Pilih layanan terlebih dahulu");

  const berat = parseFloat(beratInput.value);
  if (isNaN(berat)) return alert("Isi estimasi berat/potongan dengan benar");

  const hargaPerUnit = parseFloat(selectedService.pricePerUnit);
  const unit = selectedService.unitType;
  const deliveryOption = document.getElementById('deliveryOption').value;
  const ongkirText = document.getElementById('previewOngkir');

  let total = hargaPerUnit * berat;
  let totalDiskon = 0;
  let ongkir = 0;

  const promos = [...(currentPromo.cabang || []), ...(currentPromo.member || [])];
  promoList.innerHTML = ''; 
    promos.forEach(promo => {
    const amount = parseFloat(promo.amountPromo);
    let diskon = 0;
    let promoText = `${promo.promoName} - `;  

    if (promo.typePromo == 1) {
        diskon = total * (amount / 100);
        promoText += `Diskon ${amount}% (Rp${diskon.toLocaleString()})`;
    } else {
        diskon = amount;  
        promoText += `Diskon Rp${diskon.toLocaleString()}`;
    }

    promoList.innerHTML += `<li>${promoText}</li>`;
    totalDiskon += diskon;
  });


  const distance = parseFloat(cabangSelect.options[cabangSelect.selectedIndex].text.match(/\(([\d.]+) km\)/)?.[1] || 0);
  const ongkirWrapper = document.getElementById('ongkirWrapper');


  if (deliveryOption === '1') {
    if (distance > 1) {
      ongkir = Math.ceil(distance - 1) * 5000;
    } else {
      ongkir = 0;
    }
    ongkirText.innerText = `Rp${ongkir.toLocaleString()} (${distance.toFixed(2)} km)`;
    ongkirWrapper.style.display = 'block';
  } else {
    ongkir = 0;
    ongkirText.innerText = '-'; 
    ongkirWrapper.style.display = 'none';
  }


  const finalTotal = Math.max(total - totalDiskon, 0) + ongkir;

  layananText.innerText = selectedService.name;
  unitText.innerText = unit;
  hargaText.innerText = `Rp${hargaPerUnit.toLocaleString()}`;
  beratText.innerText = `${berat} ${unit}`;
  totalText.innerText = `Rp${finalTotal.toLocaleString()} (Diskon: Rp${totalDiskon.toLocaleString()}, Ongkir: Rp${ongkir.toLocaleString()})`;

  form.berat.value = beratInput.value;
    form.catatan.value = document.getElementById('catatan').value;
    form.deliveryOption.value = deliveryOption;
    form.ongkir.value = ongkir;
    form.paymentMethod.value = document.getElementById('paymentMethod').value;

    document.getElementById('paymentMethod').addEventListener('change', function () {
      form.paymentMethod.value = this.value;
    });
  const modal = new bootstrap.Modal(document.getElementById('modalEstimasi'));
  modal.show();
});

document.getElementById('deliveryOption').addEventListener('change', function () {
  const deliveryOption = this.value;
  const distance = parseFloat(cabangSelect.options[cabangSelect.selectedIndex].text.match(/\(([\d.]+) km\)/)?.[1] || 0);
  const ongkirText = document.getElementById('previewOngkir');
  const ongkirWrapper = ongkirText.closest('p');

  let ongkir = 0;

  if (deliveryOption === '1') {
    if (distance > 1) {
      ongkir = Math.ceil(distance - 1) * 5000;
    }
    ongkirText.innerText = `Rp${ongkir.toLocaleString()} (${distance.toFixed(2)} km)`;
    ongkirWrapper.style.display = 'block';
  } else {
    ongkirText.innerText = '-';
    ongkirWrapper.style.display = 'none';
  }


  document.querySelector('[name="ongkir"]').value = ongkir;
  document.querySelector('[name="deliveryOption"]').value = deliveryOption;
});
form.addEventListener('submit', function (e) {
   e.preventDefault();  // Prevent default form submission

   const metode = document.getElementById('paymentMethod').value;
   console.log("Payment Method Selected: " + metode);  // Debugging payment method value

   if (!metode) {
       alert('Metode pembayaran belum dipilih!');
       return;
   }

   if (metode === '1') {
       form.action = "{{ url('/transactions/order/cash') }}";
   } else if (metode === '2') {
       form.action = "{{ url('/transactions/order/saldo') }}";  // URL for saldo payment
   } else {
       alert('Metode pembayaran tidak dikenali!');
       return;
   }

   console.log('Form action after setting: ', form.action);  // Check action before submitting

   form.submit();  // Submit form after action is updated

});



  });
</script>
@endpush



@endsection

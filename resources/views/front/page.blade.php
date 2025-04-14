@extends('front.layout.template')
@section('content')
<section class="py-5 bg-light text-center">
    <div class="container">
      <h1 class="display-5 fw-bold">Layanan Laundry Cepat & Bersih</h1>
      <p class="lead">Cuci baju jadi lebih praktis. Tinggal pesan, kami jemput dan antar!</p>
      <a href="#form" class="btn btn-primary btn-lg mt-3">Pesan Sekarang</a>
    </div>
  </section>
  
  <!-- Tentang -->
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
  
  <!-- Form Pemesanan -->
  <section class="py-5 bg-light" id="form">
    <div class="container">
      <h2 class="text-center mb-4">Formulir Pemesanan</h2>
      <form>
        <div class="row g-3">
          <div class="col-md-6">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" placeholder="Nama Anda" required>
          </div>
          <div class="col-md-6">
            <label for="telepon" class="form-label">Nomor WhatsApp</label>
            <input type="tel" class="form-control" id="telepon" placeholder="08xxxx" required>
          </div>
          <div class="col-12">
            <label for="alamat" class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" rows="2" required></textarea>
          </div>
          <div class="col-md-6">
            <label for="layanan" class="form-label">Jenis Layanan</label>
            <select class="form-select" id="layanan" required>
              <option selected disabled>Pilih layanan</option>
              <option>Cuci Kering</option>
              <option>Cuci Setrika</option>
              <option>Setrika Saja</option>
              <option>Dry Cleaning</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="berat" class="form-label">Estimasi Berat / Potongan</label>
            <input type="text" class="form-control" id="berat" placeholder="Misal: 5 Kg atau 10 potong" required>
          </div>
          <div class="col-12">
            <label for="catatan" class="form-label">Catatan Tambahan</label>
            <textarea class="form-control" id="catatan" rows="2"></textarea>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-lg mt-3">Kirim Pesanan</button>
          </div>
        </div>
      </form>
    </div>
  </section>

@endsection
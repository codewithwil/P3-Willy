<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
      <a href="./index.html" class="brand-link">
          <?php
          use App\Models\Resources\Company\Company;

          $company = Company::first();
          ?>
          @if($company && $company->image)
          <img
              src="{{ asset($company->image) }}"
              alt="{{ $company->name ?? 'Company Logo' }}"
              class="shadow img-fluid rounded-circle"
              style="width: 50px; height: 50px; object-fit: cover;"
          />
          <span class="brand-text fw-light">{{ $company->name }}</span>
          @endif
      </a>
  </div>
    
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul
          class="nav sidebar-menu flex-column"
          data-lte-toggle="treeview"
          role="menu"
          data-accordion="false"
        >
          <li class="nav-item menu-open">
            <a href="/dashboard" class="nav-link active">
              <i class="nav-icon bi bi-speedometer"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-header">Transaksi</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-currency-dollar"></i>
              <p>
                transaksi
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('transactions/commodities') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Barang</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('transactions/loanings') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Peminjaman</p>
                </a>
              </li>
              @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))      
              <li class="nav-item">
                <a href="{{ url('transactions/services') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Servis Barang</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('transactions/comeCommod') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Pembelian Barang</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('transactions/serviceV') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Servis Kendaraan</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-x"></i>
                  <p>
                    Pengajuan
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(auth()->user()->hasRole(['admin', 'supervisor']))   
                  <li class="nav-item">
                    <a href="{{ url('transactions/loaningsApps') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Pengajuan Peminjaman</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('transactions/serviceApps') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Pengajuan Servis</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('transactions/comeCommodApps') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Pengajuan Pembelian Barang</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
            </ul>
          </li>
          <li class="nav-header">Resources</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-layers"></i>
              <p>
                Management Servis
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-building-gear"></i>
                  <p>
                    Manajemen Shift
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ url('resources/shift') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Shift</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('resources/empShift') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Shift Pegawai</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
          <li class="nav-header">Setting</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-layers"></i>
              <p>
                setting
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('configuration/category') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Kategori Barang</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('resources/brandMotor') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Merk Motor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('configuration/typeVehicle') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Tipe Kendaraan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('configuration/unit') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Satuan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('configuration/supplier') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Supplier</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-building-gear"></i>
                  <p>
                    Manajemen Ruangan
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ url('configuration/building') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Gudang</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('configuration/rooms') }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Ruangan</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
         
          <li class="nav-header">Laporan</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-file-x"></i>
              <p>
                Laporan
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('report/stockReport') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Riwayat Transaksi stok</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          <li class="nav-header">Konfigurasi aplikasi</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-gear"></i>
              <p>
                konfigurasi
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if(auth()->user()->hasRole(['admin', 'supervisor']))
              <li class="nav-item">
                <a href="{{ url('configuration/company') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Informasi Perusahaan</p>
                </a>
              </li>
              @endif
              @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
              <li class="nav-item">
                <a href="{{ url('people/users') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Pengguna/User</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="{{ url('attendance/presences/attendance') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Absensi</p>
                </a>
              </li>
              @if(auth()->user()->hasRole(['admin', 'supervisor']))
                <li class="nav-item">
                <a href="{{ url('attendance/presences') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Data Absensi</p>
                </a>
              </li> 
              @endif
              @if(auth()->user()->hasRole(['admin', 'supervisor']))
              <li class="nav-item">
                <a href="{{ url('attendance/locations') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Lokasi Absen</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
        </ul>
        <!--end::Sidebar Menu-->
      </nav>
    </div>
    <!--end::Sidebar Wrapper-->
  </aside>
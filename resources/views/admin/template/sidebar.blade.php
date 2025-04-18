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
            {{-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('transactions/commodities') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Member</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('transactions/loanings') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Loundry</p>
                </a>
              </li>
            </ul> --}}
          </li>
          @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
          <li class="nav-header">Setting</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-layers"></i>
              <p>
                Setting
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('setting/branch') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Cabang</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('setting/member') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Member</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('setting/promo') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Promo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('setting/service') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Layanan</p>
                </a>
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
              <li class="nav-item">
                <a href="{{ url('people/admin') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Manajemen Admin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('people/supervisor') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Manajemen Supervisor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('people/employee') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Manajemen Petugas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('people/owner') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Manajemen Owner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('people/customer') }}" class="nav-link">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Manajemen Pengguna/Pelanggan</p>
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
    <!doctype html>
    <html lang="en">
    <!--begin::Head-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Registerasi | Laundry</title>
        <!--begin::Primary Meta Tags-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="title" content="AdminLTE 4 | Register Page" />
        <meta name="author" content="ColorlibHQ" />
        <meta
        name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
        />
        <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
        />
        <!--end::Primary Meta Tags-->
        <!--begin::Fonts-->
        <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous"
        />
        <!--end::Fonts-->
        <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous"
        />
        <!--end::Third Party Plugin(OverlayScrollbars)-->
        <!--begin::Third Party Plugin(Bootstrap Icons)-->
        <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous"
        />
        <!--end::Third Party Plugin(Bootstrap Icons)-->
        <!--begin::Required Plugin(AdminLTE)-->
        <link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}" />
        <!--end::Required Plugin(AdminLTE)-->
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body class="register-page bg-body-secondary d-flex align-items-center" style="min-height: 100vh;">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="text-center mb-4">
                <?php
                use App\Models\Resources\Company\Company;
      
                $company = Company::first();
                ?>
                @if($company && $company->image)
                <img
                    src="{{ asset($company->image) }}"
                    alt="{{ $company->name ?? 'Company Logo' }}"
                    class="img-fluid rounded-circle shadow-sm mb-3" style="width: 80px;"
                />
                <h3 class="fw-bold">Daftar <span class="text-primary">{{ $company->name }}</span></h3>
                @endif

                <p class="text-muted">Silakan isi form untuk membuat akun baru</p>
              </div>
      
              @if ($errors->any())
                <div class="alert alert-danger">
                  {{ $errors->first() }}
                </div>
              @endif
      
              <div class="card shadow-sm border-0">
                <div class="card-body">
                  <form action="{{ url('register') }}" method="POST">
                    @csrf
      
                    <div class="mb-3">
                      <label for="name" class="form-label">Nama Lengkap</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required>
                      </div>
                    </div>
      
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Email aktif" required>
                      </div>
                    </div>
      
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                      </div>
                    </div>
      
                    <div class="mb-3">
                      <label for="password_confirmation" class="form-label">Ulangi Password</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                      </div>
                    </div>
      
                    <div class="mb-3 form-check">
                      <input class="form-check-input" type="checkbox" id="terms" required>
                      <label class="form-check-label" for="terms">Saya setuju dengan <a href="#">syarat & ketentuan</a></label>
                    </div>
      
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary btn-lg">Daftar Sekarang</button>
                    </div>
                  </form>
      
                  <div class="text-center mt-4">
                    <p class="mb-0">Sudah punya akun? <a href="{{ url('login') }}" class="text-decoration-none text-primary">Login di sini</a></p>
                  </div>
                </div>
              </div>
      
              @if(session('success') || session('error'))
                <script>
                  window.onload = function () {
                    @if(session('success'))
                      toastr.success('{{ session('success') }}');
                    @endif
                    @if(session('error'))
                      toastr.error('{{ session('error') }}');
                    @endif
                  };
                </script>
              @endif
            </div>
          </div>
        </div>
      </body>
      
    <!--end::Body-->
    </html>

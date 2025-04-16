<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Login Page" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}" />
  </head>
  <body class="login-page bg-body-secondary d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="text-center mb-4">
            <?php
            use App\Models\Resources\Company\Company;
  
            $company = Company::first();
            ?>
            @if($company && $company->image)
            <img
                src="{{ asset($company->image) }}"
                alt="{{ $company->name ?? 'Company Logo' }}"
                class="img-fluid rounded-circle shadow-sm mb-2" style="width: 80px;"
            />
            <h3 class="fw-bold"><span class="text-primary">{{ $company->name }}</span></h3>
            @endif
            <p class="text-muted small">Silakan login untuk melanjutkan</p>
          </div>
  
          @if ($errors->any())
            <div class="alert alert-danger">
              {{ $errors->first() }}
            </div>
          @endif
  
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <form action="{{ url('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                  </div>
                </div>
  
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                  </div>
                </div>
  
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                  </div>
                  <a href="#" class="text-decoration-none small">Lupa password?</a>
                </div>
  
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary btn-lg">Login</button>
                </div>
              </form>
  
              <div class="text-center mt-4">
                <p class="mb-0">Belum punya akun? <a href="{{ url('register') }}" class="text-primary text-decoration-none">Daftar di sini</a></p>
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

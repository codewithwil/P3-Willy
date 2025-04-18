<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Toastr CSS -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

      @stack('css')
      <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .toast-success {
            background-color: #28a745 !important; 
            color: #fff !important;
        }
    
        .toast-error {
            background-color: #dc3545 !important; 
            color: #fff !important;
        }
    
        .toast-message {
            font-size: 16px !important;
        }
    </style>
    @stack('css')
  </head>
  <body>

    @include('front.layout.header')
    @yield('content')
    @include('front.layout.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
      toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "positionClass": "toast-top-right", 
          "timeOut": "5000", 
          "extendedTimeOut": "1000",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut",
          "preventDuplicates": true,
      };
  
      @if (session('success'))
          toastr.success("{{ session('success') }}", "Success");
      @endif
  
      @if (session('error'))
          toastr.error("{{ session('error') }}", "Error");
      @endif
    </script>
    @stack('js')
  </body>
</html>

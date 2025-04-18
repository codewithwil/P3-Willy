<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">LaundryKu</a>

    <div class="d-flex align-items-center gap-2 ms-auto">
      @auth
        @if (auth()->user()->member == null)
          <a href="/configuration/member" class="btn btn-outline-light btn-sm">Daftar Member</a>
        @endif

        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="navbarDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i> Hallo, {{ optional(Auth::user()->customer)->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{url('/front/profile/'. Auth::user()->customer->customerId)}}">Profil Saya</a></li>
            <li><a class="dropdown-item" href="{{url('/front/profile/topup/'. Auth::user()->customer->customerId)}}">Top Up Saldo</a></li>
            <li><a class="dropdown-item" href="{{url('/front/profile/historySaldo/'. Auth::user()->customer->customerId)}}">History Saldo</a></li>
            <li>
              <form action="{{ route('logout') }}" method="GET" class="d-inline">
                @csrf
                <button class="dropdown-item" type="submit">Logout</button>
              </form>
            </li>
          </ul>
        </div>

        @if(auth()->user()->customer)
          <span class="text-white ms-3">
            Saldo: Rp{{ number_format(auth()->user()->customer->saldo, 0, ',', '.') }}
          </span>
        @endif

      @else
        <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
      @endauth
    </div>
  </div>
</nav>

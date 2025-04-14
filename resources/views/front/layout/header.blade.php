<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">LaundryKu</a>
  
      <div class="collapse navbar-collapse justify-content-end">
        @auth
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2"></i> {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Profil Saya</a></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="dropdown-item" type="submit">Logout</button>
                  </form>
                </li>
              </ul>
            </li>
          </ul>
        @else
          <a href="{{ route('login') }}" class="btn btn-light">Login</a>
        @endauth
      </div>
    </div>
  </nav>
  
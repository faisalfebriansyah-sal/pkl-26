<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand text-primary" href="{{ route('home') }}">
            <i class="bi bi-shop me-2"></i>
            TokoOnline
        </a>

        {{-- Toggle --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- Search --}}
            <form class="d-flex mx-auto" style="max-width: 400px; width: 100%;"
                  action="{{ route('catalog.index') }}" method="GET">
                <input type="text"
                       name="q"
                       class="form-control"
                       placeholder="Cari produk..."
                       value="{{ request('q') }}">
            </form>

            {{-- Menu --}}
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalog.index') }}">
                        Katalog
                    </a>
                </li>

                @auth
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Profil Saya
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

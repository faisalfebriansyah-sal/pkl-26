{{-- ================================================
FUNGSI: Master layout untuk halaman admin (Sneet Style)
================================================ --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Admin Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            width: 260px;
            background: #1e293b;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar .brand {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #fff;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Main content */
        .main-content {
            padding: 1rem;
            background: #f1f5f9;
            min-height: 100vh;
        }

        /* User info */
        .sidebar .user-info {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
        }

        .sidebar .user-info img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <i class="bi bi-shop fs-4"></i>
                Admin Panel
            </a>

            <nav class="flex-grow-1 mt-3">
                <ul class="nav flex-column">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-folder"></i> Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i> Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> Pengguna
                        </a>
                    </li>

                    <li class="mt-4 px-3 text-uppercase text-muted small">Laporan</li>
                    <li>
                        <a href="#" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-graph-up"></i> Laporan Penjualan
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Info --}}
            <div class="user-info">
                <img src="{{ auth()->user()->avatar_url }}" alt="avatar">
                <div>
                    <div class="fw-medium">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-grow-1">
            {{-- Topbar --}}
            <header class="topbar">
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i> Lihat Toko
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="p-4">
                {{-- @include('partials.flash-messages') --}}
            </div>

            {{-- Page Content --}}
            <div></div>
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>

{{-- aktifkan baris 89-95, 100, 111, 169 --}}
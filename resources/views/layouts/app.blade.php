{{-- =====================================================================
FILE: resources/views/layouts/app.blade.php
FUNGSI: Master layout utama (dipakai semua halaman)
===================================================================== --}}

<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Encoding karakter --}}
    <meta charset="UTF-8">

    {{-- Responsive --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token untuk form & AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title dinamis per halaman --}}
    <title>
        @yield('title', 'Beranda') - {{ config('app.name', 'Toko Online') }}
    </title>

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    {{-- Assets dari Vite --}}
    {{-- app.scss: Bootstrap + custom CSS --}}
    {{-- app.js : Bootstrap JS + custom JS --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    {{-- CSS tambahan per halaman --}}
    @stack('styles')

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    {{-- ===============================================================
    NAVBAR
    =============================================================== --}}
    @include('partials.navbar')

    {{-- ===============================================================
    FLASH MESSAGE
    =============================================================== --}}
    <div class="container mt-3">
        @include('partials.flash-messages')
    </div>

    {{-- ===============================================================
    MAIN CONTENT
    =============================================================== --}}
    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- ===============================================================
    FOOTER
    CATATAN: Footer hanya dipanggil SATU KALI di layout
    =============================================================== --}}
    @include('partials.footer')

    {{-- ===============================================================
    SCRIPT TAMBAHAN PER HALAMAN
    =============================================================== --}}
    @stack('scripts')
</body>

{{-- nonaktifkan dulu sementara baris --}}
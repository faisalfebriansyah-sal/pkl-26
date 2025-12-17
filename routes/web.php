<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//contoh
Route::get('/tentang', function () {
    return view('tentang');
});


//latihan 1
Route::get('/sapa/{nama}', function ($nama) {
   return "Halo, $nama! Selamat datang di Toko Online.";
});


//latihan 2
Route::get('/kategori/{nama?}', function ($nama = 'Semua') {
    return "Kategori komik: $nama";
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // /admin/dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');
        // ↑ Nama lengkap route: admin.dashboard
        // ↑ URL: /admin/dashboard

        // CRUD Produk: /admin/products, /admin/products/create, dll
        Route::resource('/products', AdminProductController::class);
        // ↑ resource() membuat 7 route sekaligus:
        // - GET    /admin/products          → index   (admin.products.index)
        // - GET    /admin/products/create   → create  (admin.products.create)
        // - POST   /admin/products          → store   (admin.products.store)
        // - GET    /admin/products/{id}     → show    (admin.products.show)
        // - GET    /admin/products/{id}/edit→ edit    (admin.products.edit)
        // - PUT    /admin/products/{id}     → update  (admin.products.update)
        // - DELETE /admin/products/{id}     → destroy (admin.products.destroy)
    });
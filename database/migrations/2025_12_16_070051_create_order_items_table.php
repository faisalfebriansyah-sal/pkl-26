<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke order
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete(); // kalau order dihapus, item ikut terhapus

            // Relasi ke produk
            $table->foreignId('product_id')
                  ->constrained()
                  ->restrictOnDelete(); // jangan hapus produk kalau ada di order

            // Snapshot data produk saat order
            $table->string('product_name'); // simpan nama produk saat transaksi
            $table->decimal('price', 12, 2); // harga saat transaksi
            $table->integer('quantity'); // jumlah item
            $table->decimal('subtotal', 15, 2); // quantity * price

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
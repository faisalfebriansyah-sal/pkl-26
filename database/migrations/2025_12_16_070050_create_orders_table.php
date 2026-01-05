<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // User yang melakukan order
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Nomor order unik (contoh: ORD-20251224-001)
            $table->string('order_number', 50)->unique();

            // Status Pesanan
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');

            // Status Pembayaran (unpaid, paid, failed)
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');

            // Midtrans Snap Token (jika pakai payment gateway)
            $table->string('snap_token')->nullable();

            // Total harga (termasuk ongkir)
            $table->decimal('total_amount', 15, 2);

            // Ongkos kirim
            $table->decimal('shipping_cost', 12, 2)->default(0);

            // Alamat pengiriman (snapshot saat order)
            $table->string('shipping_name');
            $table->string('shipping_phone', 20);
            $table->text('shipping_address');

            // Catatan dari pembeli (opsional)
            $table->text('notes')->nullable();

            $table->timestamps();

            // Index untuk query cepat
            $table->index('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
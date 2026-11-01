<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Satu baris per pesanan (checkout)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable()->unique(); // INV-20261029-000001, diisi setelah id ada
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('status', 20)->default('baru')->index(); // baru, dikirim, selesai, dibatalkan

            // Data pengiriman yang diisi customer saat checkout
            $table->string('recipient_name');
            $table->string('phone', 30);
            $table->text('address');
            $table->text('note')->nullable();

            $table->unsignedBigInteger('total'); // jumlah harga barang (ongkir diurus lewat email)

            $table->string('tracking_number')->nullable(); // no. resi, diisi admin saat dikirim
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            // Pengaman: stok hanya boleh dipotong sekali per pesanan
            $table->dateTime('stock_deducted_at')->nullable();

            $table->datetimes();
        });

        // Barang di dalam pesanan. Nama, varian, dan harga disalin saat checkout
        // supaya pesanan lama tidak berubah kalau produk diubah/dihapus admin.
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable(); // "Warna: Light Blue", null untuk produk tanpa varian
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('subtotal');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

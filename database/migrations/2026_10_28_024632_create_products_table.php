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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('nama');
            $table->string('slug')->unique();
            // Harga, stok, dan SKU ada di product_variants. Produk tanpa varian punya 1 varian "Default".
            $table->boolean('has_variants')->default(false);
            $table->string('variant_label', 50)->nullable();   // "Warna", "Ukuran", "Jumlah Daun"
            $table->string('variant_label_2', 50)->nullable(); // disiapkan untuk pilihan kedua (belum dipakai)
            $table->text('deskripsi')->nullable();
            $table->string('youtube_id', 20)->nullable();      // ID video YouTube, bukan link lengkap
            $table->boolean('is_active')->default(true);
            $table->datetimes();
            $table->softDeletesDatetime();

            // Nama produk unik di antara produk yang belum dihapus. Kolom ini diisi MySQL sendiri:
            // sama dengan nama, atau NULL setelah dihapus (NULL tidak dihitung dobel oleh indeks unik).
            $table->string('nama_aktif')->nullable()->storedAs('IF(deleted_at IS NULL, nama, NULL)')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

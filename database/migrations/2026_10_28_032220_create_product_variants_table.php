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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('nama'); // misal: "Light Blue", "90 x 180 cm", "2 Daun"
            $table->string('option_2', 100)->nullable(); // disiapkan untuk pilihan kedua (belum dipakai)
            $table->unsignedInteger('harga')->default(0);
            $table->unsignedInteger('harga_coret')->nullable(); // harga sebelum diskon
            $table->string('sku', 100)->nullable()->unique();
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('urutan')->default(0); // urutan di dropdown
            $table->boolean('is_active')->default(true);
            $table->datetimes();

            $table->unique(['product_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

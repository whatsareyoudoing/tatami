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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // penulis
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('sampul')->nullable();        // articles/xxx.webp (+ thumbs/)
            $table->string('ringkasan', 300)->nullable(); // tampil di daftar artikel
            $table->longText('isi');                      // HTML dari CKEditor, sudah dibersihkan
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable(); // diisi saat pertama kali diterbitkan
            $table->datetimes();

            $table->index(['is_published', 'published_at']);
        });

        // Produk yang dicantumkan di artikel, tampil sebagai kartu produk di bawah isi artikel
        Schema::create('article_product', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);

            $table->primary(['article_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_product');
        Schema::dropIfExists('articles');
    }
};

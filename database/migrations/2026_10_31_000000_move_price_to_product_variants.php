<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Harga, harga coret, dan SKU pindah dari produk ke varian.
 * Produk tanpa varian tetap punya 1 varian "Default" yang tidak ditampilkan,
 * supaya keranjang, pesanan, dan potong stok cukup bekerja per varian.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('slug');
            $table->string('variant_label', 50)->nullable()->after('has_variants');   // "Warna", "Ukuran", "Jumlah Daun"
            $table->string('variant_label_2', 50)->nullable()->after('variant_label'); // disiapkan untuk pilihan kedua (belum dipakai)
            $table->string('youtube_id', 20)->nullable()->after('deskripsi');          // ID video YouTube, bukan link lengkap
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->renameColumn('warna', 'nama');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('option_2', 100)->nullable()->after('nama'); // disiapkan untuk pilihan kedua (belum dipakai)
            $table->unsignedInteger('harga')->default(0)->after('option_2');
            $table->unsignedInteger('harga_coret')->nullable()->after('harga');
            $table->string('sku', 100)->nullable()->unique()->after('harga_coret');
        });

        // Data lama: semua produk sebelumnya memakai pilihan warna dengan harga yang sama untuk semua warna
        foreach (DB::table('products')->get(['id', 'sku', 'harga_dasar', 'harga_coret']) as $product) {
            DB::table('products')->where('id', $product->id)->update(['has_variants' => true, 'variant_label' => 'Warna']);

            DB::table('product_variants')->where('product_id', $product->id)->update([
                'harga'       => $product->harga_dasar,
                'harga_coret' => $product->harga_coret,
            ]);

            // SKU harus unik, jadi hanya dipindah ke varian pertama
            if ($product->sku) {
                $firstId = DB::table('product_variants')->where('product_id', $product->id)->orderBy('urutan')->value('id');
                DB::table('product_variants')->where('id', $firstId)->update(['sku' => $product->sku]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn(['sku', 'harga_dasar', 'harga_coret']);
        });

        // Produk tanpa varian tidak punya nama varian di pesanan
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('variant_name')->nullable()->change();
        });
        // Nama varian di pesanan lama diberi label, sama seperti pesanan baru ("Warna: Light Blue")
        DB::table('order_items')->whereNotNull('variant_name')->where('variant_name', 'not like', '%: %')
            ->update(['variant_name' => DB::raw("CONCAT('Warna: ', variant_name)")]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('category_id');
            $table->unsignedInteger('harga_dasar')->default(0)->after('slug');
            $table->unsignedInteger('harga_coret')->nullable()->after('harga_dasar');
        });

        foreach (DB::table('products')->pluck('id') as $productId) {
            $variant = DB::table('product_variants')->where('product_id', $productId)->orderBy('urutan')->first();
            if ($variant) {
                DB::table('products')->where('id', $productId)->update([
                    'harga_dasar' => $variant->harga,
                    'harga_coret' => $variant->harga_coret,
                    'sku'         => $variant->sku,
                ]);
            }
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn(['option_2', 'harga', 'harga_coret', 'sku']);
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->renameColumn('nama', 'warna');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_variants', 'variant_label', 'variant_label_2', 'youtube_id']);
        });
    }
};

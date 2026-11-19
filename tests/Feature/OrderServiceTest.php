<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_still_ships_after_one_of_its_variants_was_deleted(): void
    {
        $user = User::factory()->create();
        [$lightBlue, $green] = $this->variants(['Light Blue', 'Green'], stok: 5);
        $user->cartItems()->create(['product_variant_id' => $lightBlue->id, 'quantity' => 2]);
        $user->cartItems()->create(['product_variant_id' => $green->id, 'quantity' => 1]);
        $order = app(OrderService::class)->checkout($user, $this->shipping());

        // Admin menghapus baris "Green" di form produk sebelum pesanan dikirim
        $green->delete();

        $shipped = app(OrderService::class)->ship($order, 'JNE REG', 'RESI123');

        $this->assertSame(Order::DIKIRIM, $shipped->status);
        $this->assertSame(3, $lightBlue->fresh()->stok); // 5 - 2, dipotong sekali
    }

    public function test_order_number_uses_the_shop_date_in_wib(): void
    {
        // 23.30 UTC tanggal 17 = 06.30 WIB tanggal 18
        $this->travelTo(Carbon::parse('2026-11-17 23:30:00', 'UTC'));
        $user = User::factory()->create();
        [$variant] = $this->variants(['Light Blue'], stok: 5);
        $user->cartItems()->create(['product_variant_id' => $variant->id, 'quantity' => 1]);

        $order = app(OrderService::class)->checkout($user, $this->shipping());

        $this->assertStringStartsWith('INV-20261118-', $order->order_number);
    }

    /** @return ProductVariant[] */
    private function variants(array $names, int $stok): array
    {
        $category = Category::create(['name' => 'Bantal', 'slug' => 'bantal']);
        $product = Product::create([
            'category_id'   => $category->id,
            'nama'          => 'Pillow Igusa',
            'slug'          => 'pillow-igusa',
            'has_variants'  => true,
            'variant_label' => 'Warna',
        ]);

        return array_map(fn (string $nama) => $product->variants()->create(['nama' => $nama, 'harga' => 100000, 'stok' => $stok]), $names);
    }

    private function shipping(): array
    {
        return ['recipient_name' => 'Budi', 'phone' => '081234567890', 'address' => 'Jl. Mawar 1, Jakarta'];
    }
}

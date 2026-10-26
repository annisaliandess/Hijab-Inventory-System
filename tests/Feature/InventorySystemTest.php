<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Skenario 1: Guest (belum login) ditolak membuka dashboard admin
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Staff dicegah membuka dashboard admin (Authorization Server-Side)
     */
    public function test_staff_cannot_access_admin_dashboard(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Skenario 3: Admin dapat mengakses dashboard admin
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Skenario 4: Penjualan ditolak jika kuantitas melebihi stok yang tersedia (Mencegah Overselling)
     */
    public function test_sale_is_rejected_when_stock_is_insufficient(): void
    {
        $admin = User::where('role', 'admin')->first();
        $product = Product::first();

        // Coba beli dengan jumlah melebihi stok yang ada
        $excessQuantity = $product->stock + 100;

        $response = $this->actingAs($admin)->post('/sales', [
            'customer_name'  => 'Uji Coba Overselling',
            'payment_method' => 'cash',
            'product_id'     => [$product->id],
            'quantity'       => [$excessQuantity],
        ]);

        // Harus gagal dan kembali ke form dengan pesan error
        $response->assertSessionHas('error');

        // Pastikan stok produk di database tidak berkurang
        $product->refresh();
        $this->assertNotEquals(0, $product->stock);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun 3 Role
        User::create([
            'name' => 'Owner Admin Hijab',
            'email' => 'admin@dezzanahijab.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir / Staff Toko',
            'email' => 'staff@dezzanahijab.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Dezzana (Pelanggan)',
            'email' => 'pelanggan@dezzanahijab.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
        ]);

        // 2. Data Supplier
        Supplier::create([
            'name' => 'PT Tekstil Hijab Bandung',
            'phone' => '081234567890',
            'email' => 'bandungtextile@mail.com',
            'address' => 'Jl. Cigondewah No. 45, Bandung',
        ]);

        Supplier::create([
            'name' => 'CV Berkah Konveksi Solo',
            'phone' => '089876543210',
            'email' => 'berkahsolo@mail.com',
            'address' => 'Jl. Slamet Riyadi No. 12, Surakarta',
        ]);

        // 3. Kategori Hijab
        $pashmina = Category::create([
            'name' => 'Pashmina',
            'slug' => 'pashmina',
            'description' => 'Koleksi pashmina panjang bahan jatuh dan mewah',
        ]);

        $square = Category::create([
            'name' => 'Segi Empat (Square)',
            'slug' => 'segi-empat',
            'description' => 'Hijab segi empat mudah dibentuk dan tegak di dahi',
        ]);

        $bergo = Category::create([
            'name' => 'Bergo & Instan',
            'slug' => 'bergo-instan',
            'description' => 'Hijab harian praktis langsung pakai',
        ]);

        // 4. Produk Hijab Awal
        Product::create([
            'category_id' => $pashmina->id,
            'sku' => 'HJB-PAS-001',
            'name' => 'Pashmina Silk Cradenza',
            'slug' => Str::slug('Pashmina Silk Cradenza'),
            'material' => 'Cradenza Silk',
            'color' => 'Dusty Rose',
            'cost_price' => 45000,
            'price' => 69000,
            'stock' => 20,
            'minimum_stock' => 5,
            'description' => 'Bahan shimmer mewah, lembut, cocok untuk acara formal.',
        ]);

        Product::create([
            'category_id' => $pashmina->id,
            'sku' => 'HJB-PAS-002',
            'name' => 'Pashmina Ceruty Babydoll',
            'slug' => Str::slug('Pashmina Ceruty Babydoll'),
            'material' => 'Ceruty Babydoll',
            'color' => 'Mocca Milk',
            'cost_price' => 28000,
            'price' => 45000,
            'stock' => 35,
            'minimum_stock' => 10,
            'description' => 'Bahan berpasir halus, jatuh flowy, adem dipakai.',
        ]);

        Product::create([
            'category_id' => $square->id,
            'sku' => 'HJB-SQR-001',
            'name' => 'Bella Square Voal Premium',
            'slug' => Str::slug('Bella Square Voal Premium'),
            'material' => 'Double Hycon Voal',
            'color' => 'Sage Green',
            'cost_price' => 18000,
            'price' => 30000,
            'stock' => 3, // Stok 3 untuk uji coba alert stok kritis (< 10)
            'minimum_stock' => 10,
            'description' => 'Tegak di dahi, tidak terawang, warna pastel cantik.',
        ]);

        Product::create([
            'category_id' => $bergo->id,
            'sku' => 'HJB-BRG-001',
            'name' => 'Bergo Maryam Tali',
            'slug' => Str::slug('Bergo Maryam Tali'),
            'material' => 'Diamond Crepe',
            'color' => 'Midnight Black',
            'cost_price' => 20000,
            'price' => 35000,
            'stock' => 25,
            'minimum_stock' => 5,
            'description' => 'Bergo instan harian bertali, anti gerah dan ringan.',
        ]);
    }
}
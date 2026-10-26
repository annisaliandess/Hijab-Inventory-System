@extends('layouts.app')

@section('title', 'Tambah Produk Hijab')
@section('page-title', 'Tambah Koleksi Hijab Baru')
@section('page-subtitle', 'Masukkan spesifikasi lengkap produk, harga modal, harga jual, dan foto')

@section('header-actions')
    <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-slate-600 hover:text-navy-950 transition flex items-center gap-1">
        &larr; Kembali ke Daftar Produk
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm">
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Baris 1: Kategori & SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Hijab <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sku" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kode SKU Unik <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                        placeholder="Contoh: HJB-PAS-003"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>
            </div>

            <!-- Baris 2: Nama Produk -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Produk Hijab <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Pashmina Plisket Silk Premium"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
            </div>

            <!-- Baris 3: Bahan & Warna (Ciri Khas Dezzana Hijab) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="material" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bahan Kain
                    </label>
                    <input type="text" name="material" id="material" value="{{ old('material') }}"
                        placeholder="Contoh: Voal Ultrafine, Ceruty, Cradenza"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="color" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Varian Warna
                    </label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}"
                        placeholder="Contoh: Sage Green, Dusty Rose, Mocca"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>
            </div>

            <!-- Baris 4: Harga Modal & Harga Jual -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 rounded-2xl bg-baby-50/50 border border-baby-200/80">
                <div>
                    <label for="cost_price" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Harga Modal / Beli (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', 0) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600">
                    <span class="text-[11px] text-slate-400 mt-1 block">Biaya beli dari supplier/konveksi</span>
                </div>

                <div>
                    <label for="price" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Harga Jual Konsumen (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', 0) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600">
                    <span class="text-[11px] text-slate-400 mt-1 block">Harga tayang di kasir/katalog</span>
                </div>
            </div>

            <!-- Baris 5: Stok Awal & Stok Minimum Kritis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Stok Awal (Pcs) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="minimum_stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ambang Stok Minimum (Pcs) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', 5) }}" min="1" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                    <span class="text-[11px] text-slate-400 mt-1 block">Sistem otomatis memberi alert jika stok &le; angka ini</span>
                </div>
            </div>

            <!-- Baris 6: Upload Foto Produk -->
            <div>
                <label for="image" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Foto Produk Hijab
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/60 text-xs focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-navy-950 file:text-white hover:file:bg-baby-700">
                <span class="text-[11px] text-slate-400 mt-1 block">Format: JPG, PNG, WEBP. Maks 2MB</span>
            </div>

            <!-- Baris 7: Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Deskripsi Produk
                </label>
                <textarea name="description" id="description" rows="3"
                    placeholder="Keterangan detail jahitan tepi, kemewahan bahan, kecocokan acara..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">{{ old('description') }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold shadow-md transition">
                    Simpan Produk Hijab
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Edit Produk Hijab')
@section('page-title', 'Edit Data Produk Hijab')
@section('page-subtitle', 'Perbarui detail spesifikasi, harga jual, atau kuantitas stok')

@section('header-actions')
    <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-slate-600 hover:text-navy-950 transition flex items-center gap-1">
        &larr; Kembali ke Daftar Produk
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm">
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Baris 1: Kategori & SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Hijab <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sku" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kode SKU Unik <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>
            </div>

            <!-- Baris 2: Nama Produk -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Produk Hijab <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
            </div>

            <!-- Baris 3: Bahan & Warna -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="material" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bahan Kain
                    </label>
                    <input type="text" name="material" id="material" value="{{ old('material', $product->material) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="color" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Varian Warna
                    </label>
                    <input type="text" name="color" id="color" value="{{ old('color', $product->color) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>
            </div>

            <!-- Baris 4: Harga Modal & Harga Jual -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 rounded-2xl bg-baby-50/50 border border-baby-200/80">
                <div>
                    <label for="cost_price" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Harga Modal / Beli (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', (int)$product->cost_price) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600">
                </div>

                <div>
                    <label for="price" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Harga Jual Konsumen (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', (int)$product->price) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600">
                </div>
            </div>

            <!-- Baris 5: Stok & Ambang Minimum -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jumlah Stok (Pcs) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="minimum_stock" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Ambang Stok Minimum (Pcs) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="1" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>
            </div>

            <!-- Baris 6: Upload & Preview Gambar -->
            <div>
                <label for="image" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Ganti Foto Produk
                </label>
                @if($product->image)
                    <div class="flex items-center gap-4 mb-3 p-3 rounded-2xl bg-slate-50 border border-slate-200">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Foto aktif saat ini. Unggah file baru jika ingin menggantinya.</span>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/60 text-xs focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-navy-950 file:text-white hover:file:bg-baby-700">
            </div>

            <!-- Baris 7: Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Deskripsi Produk
                </label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold shadow-md transition">
                    Perbarui Produk Hijab
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
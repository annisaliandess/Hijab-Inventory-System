@extends('layouts.app')

@section('title', 'Katalog Produk Hijab')
@section('page-title', 'Katalog & Manajemen Stok')
@section('page-subtitle', 'Monitoring stok fisik, harga modal, harga jual, dan status ketersediaan barang')

@section('header-actions')
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Produk</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Bar Pencarian & Filter Kategori -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            
            <!-- Input Search -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, SKU, bahan, warna..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Filter Dropdown Kategori -->
            <select name="category_id" class="w-full sm:w-48 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold transition">
                Filter
            </button>

            @if(request('search') || request('category_id'))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-600 hover:underline">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-[11px] text-slate-500 flex-shrink-0">
            Total <strong>{{ $products->total() }}</strong> produk
        </div>
    </div>

    <!-- Tabel Produk Hijab -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 uppercase text-[10px] font-semibold text-slate-400 tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Produk Hijab</th>
                        <th class="px-5 py-3.5">Kategori & Bahan</th>
                        <th class="px-5 py-3.5 text-right">Harga Modal</th>
                        <th class="px-5 py-3.5 text-right">Harga Jual</th>
                        <th class="px-5 py-3.5 text-center">Status Stok</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Produk Info -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0 overflow-hidden text-slate-400">
                                        @if($p->image && file_exists(public_path($p->image)))
                                            <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-navy-950 text-xs">
                                            {{ $p->name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            SKU: {{ $p->sku }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori & Bahan -->
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                                    {{ $p->category->name }}
                                </span>
                                <div class="text-[11px] text-slate-500 mt-1">
                                    {{ $p->material ?? '-' }} &bull; <span class="text-slate-700 font-medium">{{ $p->color ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Harga Modal -->
                            <td class="px-5 py-3.5 text-right font-medium text-slate-500 font-mono">
                                Rp {{ number_format($p->cost_price, 0, ',', '.') }}
                            </td>

                            <!-- Harga Jual -->
                            <td class="px-5 py-3.5 text-right font-bold text-navy-950 font-mono">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </td>

                            <!-- Status Stok -->
                            <td class="px-5 py-3.5 text-center">
                                @if($p->isLowStock())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[11px] font-semibold border border-rose-200">
                                        {{ $p->stock }} pcs (Kritis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold border border-emerald-200">
                                        {{ $p->stock }} pcs (Aman)
                                    </span>
                                @endif
                            </td>

                            <!-- Tombol Aksi -->
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-baby-50 hover:text-baby-700 text-slate-700 font-medium text-xs transition inline-block">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk hijab ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-rose-600 hover:bg-rose-50 font-medium text-xs transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                Tidak ada produk hijab yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

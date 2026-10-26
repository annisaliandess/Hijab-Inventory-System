@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')
@section('page-title', 'Dashboard Inventaris & Penjualan')
@section('page-subtitle', 'Ringkasan performa omset, status perputaran stok, dan ketersediaan barang')

@section('content')
<!-- Header Sambutan Administrator -->
<div class="rounded-2xl bg-navy-950 text-white p-7 mb-8 border border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <span class="inline-block px-2.5 py-0.5 rounded-md bg-baby-500/20 border border-baby-400/30 text-baby-200 text-[11px] font-medium tracking-wide uppercase mb-2">
            Administrator
        </span>
        <h2 class="font-serif text-2xl font-bold">
            Selamat Datang, {{ auth()->user()->name }}
        </h2>
        <p class="text-slate-300 text-xs mt-1 max-w-xl leading-relaxed">
            Sistem operasional butik aktif. Seluruh pencatatan transaksi terikat dengan integritas stok otomatis dan pembatasan hak akses berbasis peran.
        </p>
    </div>

    <div class="flex items-center gap-2.5">
        <a href="{{ route('sales.create') }}" class="px-4 py-2 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buka Kasir</span>
        </a>
        <a href="{{ route('purchases.create') }}" class="px-4 py-2 bg-white/10 hover:bg-white/15 text-white font-semibold text-xs rounded-xl border border-white/20 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/></svg>
            <span>Input Restock</span>
        </a>
    </div>
</div>

<!-- 4 Kartu Metrik Utama -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Pendapatan / Omset -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Omset</span>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="font-mono text-2xl font-bold text-navy-950 mt-3">
            Rp {{ number_format($totalSales, 0, ',', '.') }}
        </p>
        <p class="text-[11px] text-slate-400 mt-1">{{ $salesCount }} nota transaksi selesai</p>
    </div>

    <!-- Total Koleksi Hijab -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Koleksi Produk</span>
            <div class="w-9 h-9 rounded-xl bg-baby-50 text-baby-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <p class="font-mono text-2xl font-bold text-navy-950 mt-3">
            {{ $totalProducts }}
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Item hijab terdaftar</p>
    </div>

    <!-- Pemasok Terdaftar -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Pemasok Mitra</span>
            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
        <p class="font-mono text-2xl font-bold text-navy-950 mt-3">
            {{ \App\Models\Supplier::count() }}
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Supplier aktif</p>
    </div>

    <!-- Peringatan Stok Menipis -->
    <div class="p-5 rounded-2xl {{ $lowStockProducts->count() > 0 ? 'bg-rose-50/70 border-rose-200' : 'bg-white border-slate-200/80' }} border shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold uppercase tracking-wider {{ $lowStockProducts->count() > 0 ? 'text-rose-700' : 'text-slate-400' }}">Stok Kritis</span>
            <div class="w-9 h-9 rounded-xl {{ $lowStockProducts->count() > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
        <p class="font-mono text-2xl font-bold {{ $lowStockProducts->count() > 0 ? 'text-rose-900' : 'text-navy-950' }} mt-3">
            {{ $lowStockProducts->count() }}
        </p>
        <p class="text-[11px] {{ $lowStockProducts->count() > 0 ? 'text-rose-600 font-medium' : 'text-slate-400' }} mt-1">
            {{ $lowStockProducts->count() > 0 ? 'Mendekati ambang minimum' : 'Persediaan dalam batas aman' }}
        </p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start mb-8">
    
    <!-- Tabel Peringatan Stok Menipis -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-navy-950 text-xs uppercase tracking-wider">Peringatan Ketersediaan Stok</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Produk yang berada di bawah atau sama dengan batas minimum</p>
            </div>
            @if($lowStockProducts->count() > 0)
                <a href="{{ route('purchases.create') }}" class="px-3 py-1.5 rounded-lg bg-baby-50 hover:bg-baby-100 text-baby-700 font-semibold text-xs border border-baby-200 transition">
                    + Restock
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-5">Nama Hijab</th>
                        <th class="py-3 px-5 text-center">Sisa Stok</th>
                        <th class="py-3 px-5 text-center">Batas Min.</th>
                        <th class="py-3 px-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lowStockProducts as $low)
                        <tr class="hover:bg-rose-50/30 transition">
                            <td class="py-3 px-5 font-medium text-navy-950">
                                {{ $low->name }}
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $low->category->name ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-5 text-center font-bold text-rose-600 font-mono">
                                {{ $low->stock }} pcs
                            </td>
                            <td class="py-3 px-5 text-center text-slate-500 font-mono">
                                {{ $low->minimum_stock }} pcs
                            </td>
                            <td class="py-3 px-5 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-rose-100 text-rose-700">
                                    Kritis
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                Seluruh persediaan hijab berada dalam kondisi aman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel 5 Transaksi Penjualan Terakhir -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-navy-950 text-xs uppercase tracking-wider">Transaksi Kasir Terkini</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">5 catatan penjualan terakhir di kasir butik</p>
            </div>
            <a href="{{ route('sales.index') }}" class="text-xs text-baby-600 font-semibold hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-5">No. Invoice</th>
                        <th class="py-3 px-5">Pelanggan</th>
                        <th class="py-3 px-5 text-right">Total</th>
                        <th class="py-3 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentSales as $sale)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-5 font-mono font-semibold text-baby-700">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="py-3 px-5 text-slate-700">
                                {{ $sale->customer_name }}
                            </td>
                            <td class="py-3 px-5 text-right font-bold text-navy-950 font-mono">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-5 text-center">
                                <a href="{{ route('sales.show', $sale->id) }}" class="text-[11px] font-medium text-baby-600 hover:text-baby-800">
                                    Nota &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada transaksi penjualan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

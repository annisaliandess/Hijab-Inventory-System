@extends('layouts.app')

@section('title', 'Portal Kasir & Staf')
@section('page-title', 'Portal Operasional Kasir')
@section('page-subtitle', 'Pusat pelayanan transaksi penjualan kasir dan penerimaan barang masuk')

@section('content')
<!-- Ringkasan Shift Kasir -->
<div class="rounded-2xl bg-navy-950 text-white p-7 mb-8 border border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <span class="inline-block px-2.5 py-0.5 rounded-md bg-baby-500/20 border border-baby-400/30 text-baby-200 text-[11px] font-medium tracking-wide uppercase mb-2">
            Kasir Aktif
        </span>
        <h2 class="font-serif text-2xl font-bold">
            Selamat Bertugas, {{ auth()->user()->name }}
        </h2>
        <p class="text-slate-300 text-xs mt-1 max-w-xl leading-relaxed">
            Sistem kasir siap melayani transaksi. Kuantitas stok produk di database terpotong otomatis setiap transaksi diterbitkan.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('sales.create') }}" class="px-5 py-2.5 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buka Kasir (POS)</span>
        </a>
    </div>
</div>

<!-- Metrik Hari Ini -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Transaksi Hari Ini</span>
        <p class="text-2xl font-bold font-mono text-navy-950 mt-2">
            {{ $todaySalesCount }} Transaksi
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Total nota penjualan</p>
    </div>

    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Omset Shift Ini</span>
        <p class="text-2xl font-bold font-mono text-navy-950 mt-2">
            Rp {{ number_format($todaySalesAmount, 0, ',', '.') }}
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Pendapatan penjualan harian</p>
    </div>

    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Penerimaan Stok</span>
        <a href="{{ route('purchases.create') }}" class="inline-block mt-3 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold transition">
            + Catat Barang Masuk
        </a>
    </div>
</div>

<!-- Menu Pintasan Cepat Staf -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('sales.create') }}" class="p-6 rounded-2xl bg-white border border-slate-200/80 hover:border-baby-400 shadow-sm hover:shadow-md transition flex items-start gap-4 group">
        <div class="w-10 h-10 rounded-xl bg-baby-50 text-baby-600 flex items-center justify-center flex-shrink-0 group-hover:bg-baby-600 group-hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-sm text-navy-950 group-hover:text-baby-600 transition">Pelayanan Kasir (POS)</h3>
            <p class="text-xs text-slate-500 mt-1">Hitung pesanan pelanggan, input metode pembayaran, dan cetak struk nota belanja.</p>
        </div>
    </a>

    <a href="{{ route('katalog') }}" class="p-6 rounded-2xl bg-white border border-slate-200/80 hover:border-baby-400 shadow-sm hover:shadow-md transition flex items-start gap-4 group">
        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 group-hover:bg-baby-600 group-hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-sm text-navy-950 group-hover:text-baby-600 transition">Cek Stok Koleksi Hijab</h3>
            <p class="text-xs text-slate-500 mt-1">Periksa ketersediaan stok fisik hijab dan informasi variasi warna untuk membantu pelanggan.</p>
        </div>
    </a>
</div>
@endsection

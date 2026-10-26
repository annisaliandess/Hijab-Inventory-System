@extends('layouts.app')

@section('title', 'Riwayat Transaksi Penjualan')
@section('page-title', 'Riwayat Penjualan Kasir')
@section('page-subtitle', 'Daftar seluruh nota transaksi penjualan dan rekapan kasir Dezzana Hijab')

@section('header-actions')
<a href="{{ route('sales.create') }}" class="px-4 py-2 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    <span>Buka Kasir Baru</span>
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-navy-950 text-sm uppercase tracking-wider">Arsip Nota Penjualan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Seluruh transaksi tersimpan permanen dan terikat dengan stok barang</p>
        </div>

        <!-- Form Filter & Pencarian -->
        <form method="GET" action="{{ route('sales.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice / pelanggan..."
                class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600">
            
            <select name="payment_method" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-baby-400">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>

            <input type="date" name="start_date" value="{{ request('start_date') }}" title="Tanggal Mulai"
                class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-baby-400">

            <input type="date" name="end_date" value="{{ request('end_date') }}" title="Tanggal Akhir"
                class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-baby-400">

            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs transition">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'payment_method', 'start_date', 'end_date']))
                <a href="{{ route('sales.index') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Riwayat Penjualan -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-5">No. Invoice</th>
                    <th class="py-3.5 px-5">Waktu Transaksi</th>
                    <th class="py-3.5 px-5">Kasir</th>
                    <th class="py-3.5 px-5">Pelanggan</th>
                    <th class="py-3.5 px-5">Metode</th>
                    <th class="py-3.5 px-5 text-right">Total Belanja</th>
                    <th class="py-3.5 px-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-5 font-mono font-semibold text-baby-700">
                            {{ $sale->invoice_number }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-600">
                            {{ date('d M Y, H:i', strtotime($sale->sale_date)) }}
                        </td>
                        <td class="py-3.5 px-5 text-navy-950 font-medium">
                            {{ $sale->user->name ?? 'Kasir' }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-700">
                            {{ $sale->customer_name }}
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider
                                {{ $sale->payment_method === 'cash' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $sale->payment_method === 'transfer' ? 'bg-baby-50 text-baby-700 border border-baby-200' : '' }}
                                {{ $sale->payment_method === 'qris' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}">
                                {{ $sale->payment_method }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right font-bold text-navy-950 font-mono">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <a href="{{ route('sales.show', $sale->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-baby-600 hover:text-white text-slate-700 font-medium text-[11px] transition">
                                <span>Lihat Nota</span>
                                <span>&rarr;</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400">
                            <p class="font-medium text-xs">Belum ada transaksi penjualan kasir yang tercatat.</p>
                            <a href="{{ route('sales.create') }}" class="inline-block mt-2 text-xs text-baby-600 font-semibold hover:underline">
                                + Buka Transaksi Kasir Pertama
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($sales->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $sales->links() }}
        </div>
    @endif
</div>
@endsection

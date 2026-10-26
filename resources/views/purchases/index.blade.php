@extends('layouts.app')

@section('title', 'Riwayat Pengadaan Stok')
@section('page-title', 'Pengadaan & Restock Produk')
@section('page-subtitle', 'Pencatatan barang masuk dari pemasok untuk pembaruan persediaan gudang')

@section('header-actions')
<a href="{{ route('purchases.create') }}" class="px-4 py-2 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    <span>Input Barang Masuk</span>
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-navy-950 text-sm uppercase tracking-wider">Arsip Pembelian dari Pemasok</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Daftar transaksi pengadaan stok untuk menjaga ketersediaan hijab butik</p>
        </div>

        <!-- Form Filter & Pencarian -->
        <form method="GET" action="{{ route('purchases.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice / pemasok..."
                class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600">

            <input type="date" name="start_date" value="{{ request('start_date') }}" title="Tanggal Mulai"
                class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-baby-400">

            <input type="date" name="end_date" value="{{ request('end_date') }}" title="Tanggal Akhir"
                class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-baby-400">

            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs transition">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                <a href="{{ route('purchases.index') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Riwayat Pembelian -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-5">No. Nota Restock</th>
                    <th class="py-3.5 px-5">Tanggal Masuk</th>
                    <th class="py-3.5 px-5">Pemasok</th>
                    <th class="py-3.5 px-5">Petugas Input</th>
                    <th class="py-3.5 px-5">Rincian Barang</th>
                    <th class="py-3.5 px-5 text-right">Total Biaya</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($purchases as $purchase)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-5 font-mono font-semibold text-baby-700">
                            {{ $purchase->invoice_number }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-600">
                            {{ date('d M Y', strtotime($purchase->purchase_date)) }}
                        </td>
                        <td class="py-3.5 px-5 font-semibold text-navy-950">
                            {{ $purchase->supplier->name ?? 'Supplier Dihapus' }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-600">
                            {{ $purchase->user->name ?? 'Staf' }}
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($purchase->items as $item)
                                    <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[11px]">
                                        {{ $item->product->name ?? 'Produk' }} 
                                        <strong class="text-baby-700 font-mono">(+{{ $item->quantity }})</strong>
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3.5 px-5 text-right font-bold text-navy-950 font-mono">
                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400 text-xs">
                            <p class="font-medium">Belum ada data restock pembelian barang masuk.</p>
                            <a href="{{ route('purchases.create') }}" class="inline-block mt-2 text-xs text-baby-600 font-semibold hover:underline">
                                + Input Restock Pertama dari Pemasok
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($purchases->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $purchases->links() }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Pesanan Saya')
@section('page-title', 'Riwayat Pesanan Saya')
@section('page-subtitle', 'Daftar transaksi belanja hijab dan invoice pembayaran Anda')

@section('header-actions')
<a href="{{ route('katalog') }}" class="px-4 py-2 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
    <span>Belanja di Katalog</span>
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-serif text-base font-bold text-navy-950">Daftar Transaksi</h3>
            <p class="text-xs text-slate-400 mt-0.5">Semua pesanan yang pernah Anda lakukan di Dezzana Hijab</p>
        </div>
        <span class="px-3 py-1 rounded-full bg-baby-50 text-baby-700 text-xs font-semibold border border-baby-100">
            Total {{ $sales->total() }} Pesanan
        </span>
    </div>

    @if($sales->isEmpty())
        <div class="p-12 text-center">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h4 class="font-serif text-base font-bold text-navy-950">Belum Ada Pesanan</h4>
            <p class="text-slate-500 text-xs mt-1 max-w-sm mx-auto">
                Anda belum melakukan pembelian hijab. Temukan berbagai koleksi Dezzana Hijab sekarang.
            </p>
            <div class="mt-4">
                <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                    <span>Mulai Belanja</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-600 border-b border-slate-100 text-[11px] uppercase font-semibold tracking-wider">
                        <th class="py-3 px-5">No. Invoice</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Item Produk</th>
                        <th class="py-3 px-4">Total Belanja</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4">Status Pesanan</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sales as $sale)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-5 font-mono font-semibold text-baby-700">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ date('d M Y, H:i', strtotime($sale->sale_date ?? $sale->created_at)) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700">
                                <span class="font-medium">{{ $sale->items->count() }} item</span>
                                <span class="text-slate-400 block text-[11px] truncate max-w-xs">
                                    {{ $sale->items->pluck('product.name')->filter()->join(', ') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-navy-950">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-slate-100 text-slate-700">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $sale->status ?? 'completed' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('sales.show', $sale->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-navy-950 hover:bg-baby-700 text-white font-medium text-[11px] transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Nota</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $sales->links() }}
            </div>
        @endif
    @endif

</div>
@endsection

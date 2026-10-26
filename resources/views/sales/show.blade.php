@extends('layouts.app')

@section('title', 'Struk Pembelian #' . $sale->invoice_number)
@section('page-title', 'Detail Nota Transaksi')
@section('page-subtitle', 'Bukti pembayaran sah Dezzana Hijab')

@section('header-actions')
<div class="flex items-center gap-2 print:hidden">
    @if(auth()->user()->isCustomer())
        <a href="{{ route('customer.orders') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
            &larr; Pesanan Saya
        </a>
        <a href="{{ route('katalog') }}" class="px-3.5 py-2 rounded-xl bg-baby-50 border border-baby-200 text-xs font-semibold text-baby-700 hover:bg-baby-100 transition">
            Belanja Lagi
        </a>
    @else
        <a href="{{ route('sales.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
            &larr; Riwayat Penjualan
        </a>
        <a href="{{ route('sales.create') }}" class="px-3.5 py-2 rounded-xl bg-baby-50 border border-baby-200 text-xs font-semibold text-baby-700 hover:bg-baby-100 transition">
            + Transaksi Baru
        </a>
    @endif
    <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-navy-950 text-white text-xs font-semibold hover:bg-navy-800 transition shadow-sm flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        <span>Cetak Nota</span>
    </button>
</div>
@endsection

@section('content')
<style>
    @media print {
        /* Sembunyikan navigasi, sidebar, header, footer, dan tombol aksi */
        aside, header, footer, .print\:hidden, nav {
            display: none !important;
        }

        /* Reset layout body dan wrapper agar receipt tidak terpotong atau tergeser margin sidebar */
        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
            display: block !important;
        }

        .ml-64 {
            margin-left: 0 !important;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .max-w-xl {
            max-width: 100% !important;
            margin: 0 auto !important;
        }

        /* Format Struk Rapi saat Dicetak */
        #printableReceipt {
            box-shadow: none !important;
            border: 1px dashed #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 24px !important;
            max-width: 580px !important;
            margin: 0 auto !important;
            background: #ffffff !important;
            page-break-inside: avoid;
        }

        /* Pastikan warna teks & border tercetak dengan jelas */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="max-w-xl mx-auto">
    <!-- Kartu Nota Transaksi -->
    <div id="printableReceipt" class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm">
        
        <!-- Header Struk Butik -->
        <div class="text-center pb-6 border-b border-dashed border-slate-200">
            <div class="w-10 h-10 mx-auto rounded-xl bg-navy-950 text-white flex items-center justify-center font-serif text-lg font-bold mb-2 shadow-sm">
                D
            </div>
            <h2 class="font-serif text-lg font-bold text-navy-950">Dezzana Hijab</h2>
            <p class="text-[11px] text-slate-500 mt-0.5">Koleksi Busana Muslimah & Hijab Modern</p>
            <p class="text-[10px] text-slate-400">Jl. Melati Raya No. 12 &bull; Telp: (021) 8899-7766</p>
        </div>

        <!-- Info Metadata Transaksi -->
        <div class="grid grid-cols-2 gap-4 py-4 text-xs border-b border-slate-100">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-semibold tracking-wider">No. Invoice</span>
                <span class="font-mono font-semibold text-baby-700 text-xs">{{ $sale->invoice_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px] uppercase font-semibold tracking-wider">Waktu Transaksi</span>
                <span class="font-medium text-slate-700">{{ $sale->created_at ? $sale->created_at->format('d M Y, H:i') : date('d M Y, H:i', strtotime($sale->sale_date)) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-semibold tracking-wider">Nama Pelanggan</span>
                <span class="font-semibold text-navy-950">{{ $sale->customer_name }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px] uppercase font-semibold tracking-wider">Layanan / Kasir</span>
                <span class="font-medium text-slate-700">{{ $sale->user?->name ?? ($sale->customer_id ? 'Self-Checkout Online' : 'Kasir Toko') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-semibold tracking-wider">Status Pesanan</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    {{ $sale->status ?? 'completed' }}
                </span>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px] uppercase font-semibold tracking-wider">Status Pembayaran</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-baby-50 text-baby-700 border border-baby-200 uppercase">
                    {{ $sale->payment_status ?? 'paid' }}
                </span>
            </div>
        </div>

        <!-- Tabel Rincian Hijab yang Dibeli -->
        <div class="py-4">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-[10px] uppercase font-semibold text-slate-400 border-b border-slate-100 pb-2">
                        <th class="pb-2">Deskripsi Produk</th>
                        <th class="pb-2 text-center">Harga</th>
                        <th class="pb-2 text-center">Qty</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sale->items as $item)
                        <tr>
                            <td class="py-2.5 font-medium text-navy-950">
                                {{ $item->product?->name ?? 'Produk Dihapus' }}
                                <span class="block text-[10px] text-slate-400 font-normal">
                                    {{ $item->product?->color ?? '' }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center text-slate-600 font-mono text-[11px]">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 text-center font-semibold text-slate-800">
                                {{ $item->quantity }}
                            </td>
                            <td class="py-2.5 text-right font-semibold text-navy-950 font-mono text-xs">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Total & Pembayaran -->
        <div class="pt-4 border-t-2 border-slate-200/80 space-y-2 text-xs">
            <div class="flex justify-between items-center text-slate-600">
                <span>Metode Pembayaran</span>
                <span class="font-semibold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-[10px]">
                    {{ $sale->payment_method }}
                </span>
            </div>

            <div class="flex justify-between items-center pt-2 border-t border-slate-100 text-sm">
                <span class="font-bold text-navy-950">TOTAL PEMBAYARAN</span>
                <span class="font-mono font-bold text-baby-700 text-base">
                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        @if($sale->notes)
            <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 text-[11px] text-slate-500">
                <strong class="text-slate-700 block">Catatan:</strong>
                {{ $sale->notes }}
            </div>
        @endif

        <!-- Footer Struk -->
        <div class="text-center mt-6 pt-5 border-t border-dashed border-slate-200 text-slate-400 text-[11px]">
            <p class="font-medium text-slate-600">Terima kasih atas kunjungan Anda di Dezzana Hijab.</p>
            <p class="text-[10px] mt-0.5">Penukaran barang maksimal 2 hari kerja dengan menyertakan nota ini.</p>
        </div>

    </div>
</div>
@endsection

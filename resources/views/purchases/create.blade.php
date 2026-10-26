@extends('layouts.app')

@section('title', 'Form Pengadaan Stok')
@section('page-title', 'Input Penerimaan Barang (Restock)')
@section('page-subtitle', 'Pencatatan pasokan produk dari supplier untuk pembaruan kuantitas stok')

@section('content')
<form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Kolom Kiri: Daftar Barang -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-navy-950 text-sm">Daftar Produk Masuk</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Tentukan varian hijab yang dipasok, jumlah unit, dan harga modal beli</p>
                    </div>

                    <button type="button" onclick="addPurchaseRow()" class="px-3.5 py-1.5 bg-baby-50 hover:bg-baby-100 text-baby-700 font-semibold text-xs rounded-xl border border-baby-200 transition flex items-center gap-1.5">
                        <span class="text-sm font-bold">+</span> Tambah Baris
                    </button>
                </div>

                <!-- Tabel Item Restock -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="purchaseTable">
                        <thead>
                            <tr class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                                <th class="pb-3 w-5/12">Produk Hijab</th>
                                <th class="pb-3 text-center w-24">Jumlah (Pcs)</th>
                                <th class="pb-3 text-right w-36">Harga Modal (Rp)</th>
                                <th class="pb-3 text-right w-36">Subtotal</th>
                                <th class="pb-3 text-center w-12">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="purchaseBody" class="divide-y divide-slate-100">
                            <!-- Baris Pertama Default -->
                            <tr class="purchase-row">
                                <td class="py-3 pr-2">
                                    <select name="product_id[]" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium text-slate-700 bg-white">
                                        <option value="" disabled selected>-- Pilih Produk Hijab --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->name }} (Stok saat ini: {{ $p->stock }} pcs)
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <input type="number" name="quantity[]" value="10" min="1" required oninput="calcRow(this)" class="qty-restock w-20 text-center px-2 py-1.5 text-xs font-semibold rounded-xl border border-slate-200 focus:ring-2 focus:ring-baby-400 outline-none">
                                </td>
                                <td class="py-3 px-2 text-right">
                                    <input type="number" name="unit_cost[]" value="35000" min="0" step="500" required oninput="calcRow(this)" placeholder="0" class="cost-restock w-32 text-right px-3 py-1.5 text-xs font-mono font-semibold rounded-xl border border-slate-200 focus:ring-2 focus:ring-baby-400 outline-none">
                                </td>
                                <td class="py-3 pl-2 text-right font-bold text-navy-950 text-xs subtotal-restock font-mono">
                                    Rp 350.000
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" onclick="removePurchaseRow(this)" class="text-slate-300 hover:text-rose-500 transition p-1 text-sm font-bold" title="Hapus baris">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Catatan Pengiriman -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <label class="block text-xs font-semibold text-navy-950 uppercase tracking-wider mb-2">Catatan Pengiriman / Surat Jalan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Nomor resi, keterangan fisik paket, atau catatan pemasok..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-baby-400 resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Kolom Kanan: Metadata Restock -->
        <div class="space-y-6">
            <div class="bg-navy-950 text-white rounded-2xl p-6 border border-slate-800 shadow-sm">
                <h3 class="font-serif text-base font-bold text-white mb-4 pb-3 border-b border-slate-800">Detail Pengadaan</h3>

                <!-- Pilih Supplier -->
                <div class="mb-4">
                    <label class="block text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pemasok (Supplier)</label>
                    <select name="supplier_id" required class="w-full px-3.5 py-2 bg-navy-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium">
                        <option value="" disabled selected>-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} ({{ $supplier->contact_person ?? 'Kontak' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Masuk -->
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tanggal Penerimaan</label>
                    <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2 bg-navy-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium">
                </div>

                <!-- Estimasi Total -->
                <div class="bg-navy-900 rounded-xl p-4 border border-slate-800 mb-5">
                    <span class="text-[11px] text-slate-400 font-medium">Estimasi Biaya Pengadaan</span>
                    <div class="text-2xl font-bold text-baby-300 font-mono mt-1" id="totalRestockDisplay">
                        Rp 350.000
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full py-3 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Simpan & Tambah Stok Masuk</span>
                </button>
            </div>
        </div>

    </div>
</form>

<!-- Template Baris Restock -->
<template id="purchaseRowTemplate">
    <tr class="purchase-row">
        <td class="py-3 pr-2">
            <select name="product_id[]" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium text-slate-700 bg-white">
                <option value="" disabled selected>-- Pilih Produk Hijab --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->name }} (Stok saat ini: {{ $p->stock }} pcs)
                    </option>
                @endforeach
            </select>
        </td>
        <td class="py-3 px-2 text-center">
            <input type="number" name="quantity[]" value="10" min="1" required oninput="calcRow(this)" class="qty-restock w-20 text-center px-2 py-1.5 text-xs font-semibold rounded-xl border border-slate-200 focus:ring-2 focus:ring-baby-400 outline-none">
        </td>
        <td class="py-3 px-2 text-right">
            <input type="number" name="unit_cost[]" value="35000" min="0" step="500" required oninput="calcRow(this)" placeholder="0" class="cost-restock w-32 text-right px-3 py-1.5 text-xs font-mono font-semibold rounded-xl border border-slate-200 focus:ring-2 focus:ring-baby-400 outline-none">
        </td>
        <td class="py-3 pl-2 text-right font-bold text-navy-950 text-xs subtotal-restock font-mono">
            Rp 350.000
        </td>
        <td class="py-3 text-center">
            <button type="button" onclick="removePurchaseRow(this)" class="text-slate-300 hover:text-rose-500 transition p-1 text-sm font-bold" title="Hapus baris">&times;</button>
        </td>
    </tr>
</template>

<script>
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calcRow(el) {
        const row = el.closest('tr');
        const qty = parseInt(row.querySelector('.qty-restock').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-restock').value) || 0;
        const subtotal = qty * cost;

        row.querySelector('.subtotal-restock').textContent = formatRupiah(subtotal);
        updateGrandTotalRestock();
    }

    function updateGrandTotalRestock() {
        let grandTotal = 0;
        document.querySelectorAll('.purchase-row').forEach(row => {
            const qty = parseInt(row.querySelector('.qty-restock').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-restock').value) || 0;
            grandTotal += (qty * cost);
        });

        document.getElementById('totalRestockDisplay').textContent = formatRupiah(grandTotal);
    }

    function addPurchaseRow() {
        const template = document.getElementById('purchaseRowTemplate');
        const clone = template.content.cloneNode(true);
        document.getElementById('purchaseBody').appendChild(clone);
        updateGrandTotalRestock();
    }

    function removePurchaseRow(btn) {
        const rows = document.querySelectorAll('.purchase-row');
        if (rows.length <= 1) {
            alert('Minimal harus ada 1 item dalam pengadaan barang masuk.');
            return;
        }
        btn.closest('tr').remove();
        updateGrandTotalRestock();
    }

    document.addEventListener('DOMContentLoaded', updateGrandTotalRestock);
</script>
@endsection

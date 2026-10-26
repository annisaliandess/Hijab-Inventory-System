@extends('layouts.app')

@section('title', 'Kasir POS - Penjualan Hijab')
@section('page-title', 'Kasir Penjualan (Point of Sale)')
@section('page-subtitle', 'Pencatatan transaksi kasir, cetak struk nota belanja, dan pemotongan stok otomatis')

@section('content')
<form action="{{ route('sales.store') }}" method="POST" id="posForm">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Kolom Kiri: Keranjang Belanja -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-navy-950 text-sm">Daftar Item Belanja</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pilih produk hijab dan masukkan jumlah kuantitas yang dibeli</p>
                    </div>

                    <button type="button" onclick="addRow()" class="px-3.5 py-1.5 bg-baby-50 hover:bg-baby-100 text-baby-700 font-semibold text-xs rounded-xl border border-baby-200 transition flex items-center gap-1.5">
                        <span class="text-sm font-bold">+</span> Tambah Baris
                    </button>
                </div>

                <!-- Tabel Item Keranjang Kasir -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="itemsTable">
                        <thead>
                            <tr class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                                <th class="pb-3 w-1/2">Produk Hijab</th>
                                <th class="pb-3 text-center w-24">Stok</th>
                                <th class="pb-3 text-center w-24">Qty</th>
                                <th class="pb-3 text-right w-32">Subtotal</th>
                                <th class="pb-3 text-center w-12">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody" class="divide-y divide-slate-100">
                            <!-- Baris Pertama Default -->
                            <tr class="item-row">
                                <td class="py-3 pr-2">
                                    <select name="product_id[]" required onchange="handleProductChange(this)" class="product-select w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium text-slate-700 bg-white">
                                        <option value="" disabled selected>-- Pilih Koleksi Hijab --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}">
                                                {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3 px-2 text-center text-xs font-semibold text-slate-500 stock-badge">
                                    -
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <input type="number" name="quantity[]" value="1" min="1" max="1" required oninput="calculateRow(this)" class="qty-input w-20 text-center px-2 py-1.5 text-xs font-semibold rounded-xl border border-slate-200 focus:ring-2 focus:ring-baby-400 outline-none">
                                </td>
                                <td class="py-3 pl-2 text-right font-bold text-navy-950 text-xs subtotal-text font-mono">
                                    Rp 0
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" onclick="removeRow(this)" class="text-slate-300 hover:text-rose-500 transition p-1 text-sm font-bold" title="Hapus item">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Catatan Pesanan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <label class="block text-xs font-semibold text-navy-950 uppercase tracking-wider mb-2">Catatan Pesanan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan khusus pelanggan atau detail pesanan..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-baby-400 resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Pembayaran -->
        <div class="space-y-6">
            <div class="bg-navy-950 text-white rounded-2xl p-6 border border-slate-800 shadow-sm">
                <h3 class="font-serif text-base font-bold text-white mb-4 pb-3 border-b border-slate-800">Pembayaran Kasir</h3>

                <!-- Input Nama Pelanggan -->
                <div class="mb-4">
                    <label class="block text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nama Pelanggan</label>
                    <input type="text" name="customer_name" required value="{{ old('customer_name', 'Pelanggan Umum') }}" placeholder="Contoh: Ibu Fatimah" class="w-full px-3.5 py-2 bg-navy-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium">
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-5">
                    <label class="block text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" checked class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl bg-navy-900 border border-slate-700 text-xs font-medium peer-checked:bg-baby-600 peer-checked:border-baby-500 peer-checked:text-white transition hover:bg-navy-800">
                                Tunai
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="transfer" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl bg-navy-900 border border-slate-700 text-xs font-medium peer-checked:bg-baby-600 peer-checked:border-baby-500 peer-checked:text-white transition hover:bg-navy-800">
                                Transfer
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="qris" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl bg-navy-900 border border-slate-700 text-xs font-medium peer-checked:bg-baby-600 peer-checked:border-baby-500 peer-checked:text-white transition hover:bg-navy-800">
                                QRIS
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Total Tagihan -->
                <div class="bg-navy-900 rounded-xl p-4 border border-slate-800 mb-5">
                    <span class="text-[11px] text-slate-400 font-medium">Total Pembayaran</span>
                    <div class="text-2xl font-bold text-baby-300 font-mono mt-1" id="grandTotalDisplay">
                        Rp 0
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" id="btnSubmit" class="w-full py-3 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan & Terbitkan Struk</span>
                </button>
            </div>
        </div>

    </div>
</form>

<!-- Template Baris Produk -->
<template id="rowTemplate">
    <tr class="item-row">
        <td class="py-3 pr-2">
            <select name="product_id[]" required onchange="handleProductChange(this)" class="product-select w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-baby-400 font-medium text-slate-700 bg-white">
                <option value="" disabled selected>-- Pilih Koleksi Hijab --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}">
                        {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </td>
        <td class="py-3 px-2 text-center text-xs font-semibold text-slate-500 stock-badge">
            -
        </td>
        <td class="py-3 px-2 text-center">
            <input type="number" name="quantity[]" value="1" min="1" max="1" required oninput="calculateRow(this)" class="qty-input w-20 text-center px-2 py-1.5 text-xs font-semibold rounded-xl border border-slate-200 focus:ring-2 focus:ring-baby-400 outline-none">
        </td>
        <td class="py-3 pl-2 text-right font-bold text-navy-950 text-xs subtotal-text font-mono">
            Rp 0
        </td>
        <td class="py-3 text-center">
            <button type="button" onclick="removeRow(this)" class="text-slate-300 hover:text-rose-500 transition p-1 text-sm font-bold" title="Hapus item">&times;</button>
        </td>
    </tr>
</template>

<script>
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function handleProductChange(select) {
        const row = select.closest('tr');
        const selectedOption = select.options[select.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const qtyInput = row.querySelector('.qty-input');
        const stockBadge = row.querySelector('.stock-badge');

        stockBadge.textContent = stock + ' pcs';
        qtyInput.max = stock;
        if (parseInt(qtyInput.value) > stock) {
            qtyInput.value = stock;
        }

        calculateRow(qtyInput);
    }

    function calculateRow(input) {
        const row = input.closest('tr');
        const select = row.querySelector('.product-select');
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption || select.value === "") return;

        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        let qty = parseInt(input.value) || 0;

        if (qty > stock) {
            alert('Kuantitas melebihi stok yang tersedia (' + stock + ' pcs)');
            qty = stock;
            input.value = stock;
        }

        const subtotal = price * qty;
        row.querySelector('.subtotal-text').textContent = formatRupiah(subtotal);
        row.dataset.subtotal = subtotal;

        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            if (select && select.value !== "") {
                const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
                total += (price * qty);
            }
        });

        document.getElementById('grandTotalDisplay').textContent = formatRupiah(total);
    }

    function addRow() {
        const template = document.getElementById('rowTemplate');
        const clone = template.content.cloneNode(true);
        document.getElementById('cartBody').appendChild(clone);
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Minimal harus ada 1 produk dalam transaksi kasir.');
            return;
        }
        btn.closest('tr').remove();
        updateGrandTotal();
    }
</script>
@endsection

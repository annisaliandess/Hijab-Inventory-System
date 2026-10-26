@extends('layouts.app')

@section('title', 'Pemasok & Konveksi')
@section('page-title', 'Manajemen Supplier & Konveksi')
@section('page-subtitle', 'Kelola data mitra pemasok kain, konveksi, dan distributor hijab')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Kolom Kiri: Form Tambah Supplier -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-8 h-8 rounded-lg bg-baby-50 text-baby-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="font-serif text-base font-bold text-navy-950">Tambah Supplier Baru</h3>
            </div>
            <p class="text-xs text-slate-500 mb-5">Daftarkan mitra pabrik kain atau konveksi baru.</p>

            <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Perusahaan / Konveksi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: PT Tekstil Bandung"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="phone" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                        placeholder="081234567890"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="email" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        placeholder="kontak@tekstil.com"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="address" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Pabrik / Gudang
                    </label>
                    <textarea name="address" id="address" rows="3"
                        placeholder="Alamat lengkap..."
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold shadow-sm transition flex items-center justify-center gap-1.5">
                    <span>+ Simpan Supplier</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Daftar Tabel Supplier -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-navy-950 text-xs uppercase tracking-wider">Daftar Pemasok Terdaftar</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Total {{ $suppliers->total() }} supplier aktif</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/75 uppercase text-[10px] font-semibold text-slate-400 tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">Nama Pemasok</th>
                            <th class="px-5 py-3">Kontak</th>
                            <th class="px-5 py-3">Alamat</th>
                            <th class="px-5 py-3 text-center">Riwayat Restock</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($suppliers as $sup)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-semibold text-navy-950">
                                    {{ $sup->name }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-mono text-slate-800">{{ $sup->phone }}</p>
                                    @if($sup->email)
                                        <p class="text-[11px] text-slate-400">{{ $sup->email }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">
                                    {{ $sup->address ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-baby-50 text-baby-700 font-semibold text-xs border border-baby-200">
                                        {{ $sup->purchases_count }} kali
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <form action="{{ route('admin.suppliers.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-rose-600 hover:bg-rose-50 text-xs font-medium transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada data supplier yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($suppliers->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

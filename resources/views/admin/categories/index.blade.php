@extends('layouts.app')

@section('title', 'Kategori Hijab')
@section('page-title', 'Manajemen Kategori Hijab')
@section('page-subtitle', 'Kelola kelompok koleksi busana (Pashmina, Segi Empat, Bergo, Khimar)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Kolom Kiri: Form Tambah Kategori -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-8 h-8 rounded-lg bg-baby-50 text-baby-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <h3 class="font-serif text-base font-bold text-navy-950">Tambah Kategori</h3>
            </div>
            <p class="text-xs text-slate-500 mb-5">Tambahkan kelompok koleksi hijab baru ke katalog butik.</p>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Khimar Syar'i"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">
                </div>

                <div>
                    <label for="description" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Deskripsi
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Keterangan singkat tentang kelompok hijab ini..."
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold shadow-sm transition flex items-center justify-center gap-1.5">
                    <span>+ Simpan Kategori</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Daftar Tabel Kategori -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-navy-950 text-xs uppercase tracking-wider">Daftar Kategori Aktif</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Total {{ $categories->total() }} kategori terdaftar di sistem</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/75 uppercase text-[10px] font-semibold text-slate-400 tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">Nama Kategori</th>
                            <th class="px-5 py-3">Slug URL</th>
                            <th class="px-5 py-3 text-center">Jumlah Hijab</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-semibold text-navy-950">
                                    {{ $cat->name }}
                                    @if($cat->description)
                                        <p class="text-[11px] font-normal text-slate-400 mt-0.5">{{ $cat->description }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-[11px] font-mono text-slate-600">
                                        /{{ $cat->slug }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-baby-50 text-baby-700 font-semibold text-xs border border-baby-200">
                                        {{ $cat->products_count }} Hijab
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
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
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada kategori hijab yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

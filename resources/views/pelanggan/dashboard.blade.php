@extends('layouts.app')

@section('title', 'Beranda Pelanggan')
@section('page-title', 'Selamat Datang di Dezzana Hijab')
@section('page-subtitle', 'Koleksi busana muslimah dan hijab elegan kualitas butik')

@section('content')
<!-- Banner Sambutan Pelanggan -->
<div class="rounded-2xl bg-navy-950 text-white p-7 mb-8 border border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="max-w-xl">
        <span class="inline-block px-2.5 py-0.5 rounded-md bg-baby-500/20 border border-baby-400/30 text-baby-200 text-[11px] font-medium tracking-wide uppercase mb-2">
            Pelanggan Butik
        </span>
        <h2 class="font-serif text-2xl font-bold">
            Selamat Datang, {{ auth()->user()->name }}
        </h2>
        <p class="text-slate-300 text-xs mt-1.5 leading-relaxed">
            Jelajahi pilihan hijab premium dengan material lembut, jahitan rapi, dan warna anggun yang siap melengkapi busana harian Anda.
        </p>
        <div class="mt-5">
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-baby-600 hover:bg-baby-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Buka Katalog Lengkap</span>
            </a>
        </div>
    </div>
    
    <div class="hidden md:block text-right">
        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 text-center">
            <span class="text-xs text-baby-200 uppercase tracking-widest font-semibold block">Dezzana Hijab</span>
            <span class="text-xs text-slate-400 mt-1 block">Modest Wear & Boutique</span>
        </div>
    </div>
</div>

<!-- Kategori Hijab -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-serif text-base font-bold text-navy-950">Kategori Pilihan</h3>
        <a href="{{ route('katalog') }}" class="text-xs text-baby-600 font-semibold hover:underline">Semua Kategori &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($categories as $cat)
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-navy-950 text-xs">{{ $cat->name }}</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $cat->products_count }} produk</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-baby-50 text-baby-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Koleksi Terbaru -->
<div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-serif text-base font-bold text-navy-950">Koleksi Terbaru</h3>
        <a href="{{ route('katalog') }}" class="text-xs text-baby-600 font-semibold hover:underline">Lihat Semua di Katalog &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
        @foreach($featuredProducts as $item)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="h-32 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-3 overflow-hidden">
                        @if($item->image && file_exists(public_path($item->image)))
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold">
                        {{ $item->category->name ?? 'Hijab' }}
                    </span>
                    <h4 class="font-semibold text-navy-950 text-xs mt-1.5 truncate">{{ $item->name }}</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $item->material ?? 'Material lembut' }}</p>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="font-bold text-navy-950">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-slate-400">Tersedia {{ $item->stock }} pcs</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

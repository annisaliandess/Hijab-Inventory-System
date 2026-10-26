<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem') - Dezzana Hijab</title>
    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        navy: {
                            800: '#0F1E36',
                            900: '#0A1526',
                            950: '#060D18',
                        },
                        baby: {
                            50: '#F0F7FF',
                            100: '#E0F0FE',
                            200: '#BAE0FD',
                            300: '#7DC4FC',
                            400: '#38A5F8',
                            500: '#0E87E8',
                            600: '#026BC7',
                            700: '#0355A0',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen flex">

    <!-- Sidebar Navigasi -->
    <aside class="w-64 bg-navy-950 text-slate-300 min-h-screen flex flex-col justify-between p-5 fixed top-0 bottom-0 left-0 z-40 border-r border-slate-800/80">
        <div>
            <!-- Brand Logo Dezzana Hijab -->
            <div class="flex items-center gap-3 px-2 py-3 border-b border-slate-800/80 mb-6">
                <span class="w-9 h-9 rounded-xl bg-baby-600 text-white flex items-center justify-center font-serif text-lg font-bold shadow-sm">D</span>
                <div>
                    <h1 class="font-serif text-base font-bold text-white tracking-wide leading-none">Dezzana Hijab</h1>
                    <span class="text-[10px] text-baby-300 font-medium tracking-widest uppercase">Hijab Management</span>
                </div>
            </div>

            <!-- Navigasi Dinamis Sesuai Role -->
            <nav class="space-y-1">
                
                {{-- Menu Admin --}}
                @if(auth()->user()->isAdmin())
                    <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider mb-1.5">Menu Utama</p>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider mt-5 mb-1.5">Master Data</p>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Katalog Produk</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Kategori Hijab</span>
                    </a>

                    <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Data Pemasok</span>
                    </a>

                    <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider mt-5 mb-1.5">Transaksi</p>

                    <a href="{{ route('sales.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('sales.create') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Kasir (POS)</span>
                    </a>

                    <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Riwayat Penjualan</span>
                    </a>

                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/></svg>
                        <span>Pengadaan Stok</span>
                    </a>

                    <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider mt-5 mb-1.5">Tautan Luar</p>
                    <a href="{{ route('katalog') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-navy-800 hover:text-white transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Katalog Publik</span>
                        </span>
                        <span class="text-xs text-slate-500">&nearr;</span>
                    </a>

                {{-- Menu Staff --}}
                @elseif(auth()->user()->isStaff())
                    <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider mb-1.5">Operasional</p>

                    <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('staff.dashboard') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Portal Kasir</span>
                    </a>

                    <a href="{{ route('sales.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('sales.create') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Transaksi Kasir</span>
                    </a>

                    <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Riwayat Penjualan</span>
                    </a>

                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/></svg>
                        <span>Penerimaan Barang</span>
                    </a>

                    <a href="{{ route('katalog') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-navy-800 hover:text-white transition mt-3">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Cek Stok Katalog</span>
                    </a>

                {{-- Menu Pelanggan --}}
                @else
                    <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider mb-1.5">Area Member</p>

                    <a href="{{ route('pelanggan.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium {{ request()->routeIs('pelanggan.dashboard') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }} transition">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('katalog') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium {{ request()->routeIs('katalog') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }} transition">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Katalog Hijab</span>
                    </a>

                    <a href="{{ route('customer.orders') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium {{ request()->routeIs('customer.orders*') || request()->routeIs('pelanggan.orders*') ? 'bg-baby-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-800 hover:text-white' }} transition">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Pesanan Saya</span>
                    </a>
                @endif

            </nav>
        </div>

        <!-- Profil Pengguna & Logout -->
        <div class="pt-4 border-t border-slate-800/80">
            <div class="flex items-center gap-3 px-2 py-2 mb-2 bg-navy-900/60 rounded-xl border border-slate-800/60">
                <div class="w-8 h-8 rounded-lg bg-baby-500/20 border border-baby-400/30 text-baby-300 flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase mt-0.5
                        {{ auth()->user()->role === 'admin' ? 'bg-baby-950 text-baby-300 border border-baby-700/50' : '' }}
                        {{ auth()->user()->role === 'staff' ? 'bg-amber-950 text-amber-300 border border-amber-700/50' : '' }}
                        {{ auth()->user()->role === 'pelanggan' ? 'bg-emerald-950 text-emerald-300 border border-emerald-700/50' : '' }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Keluar dari sesi sistem?')">
                @csrf
                <button type="submit" class="w-full px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-rose-300 hover:bg-rose-950/40 border border-transparent hover:border-rose-900/40 transition flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten Utama -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Topbar -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-8 py-3.5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-navy-950">@yield('page-title', 'Dashboard')</h2>
                <p class="text-[11px] text-slate-500">@yield('page-subtitle', 'Sistem Manajemen Dezzana Hijab')</p>
            </div>
            
            <div class="flex items-center gap-3">
                @yield('header-actions')

                <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs text-slate-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="capitalize">{{ auth()->user()->role }}</span>
                </div>

                <!-- Tombol Logout Cepat di Topbar -->
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Keluar dari sistem?')">
                    @csrf
                    <button type="submit" title="Logout Cepat" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-transparent hover:border-rose-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Area Konten -->
        <main class="flex-1 p-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5 shadow-sm">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-sm">
                    <div class="font-bold mb-1">Periksa kembali data Anda:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white px-8 py-3.5 text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} Dezzana Hijab &bull; Sistem Inventaris & Kasir
        </footer>

    </div>

</body>
</html>

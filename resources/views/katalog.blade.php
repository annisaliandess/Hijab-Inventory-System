@if(auth()->check())
    @extends('layouts.app')

    @section('title', 'Katalog Hijab')
    @section('page-title', 'Koleksi Dezzana Hijab')
    @section('page-subtitle', 'Daftar busana muslimah dan hijab dengan status ketersediaan stok real-time')

    @section('content')
    <div class="space-y-6">
        
        <!-- Banner Katalog -->
        <div class="rounded-2xl bg-navy-950 text-white p-7 shadow-sm border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="inline-block px-2.5 py-0.5 rounded-md bg-baby-500/20 border border-baby-400/30 text-baby-200 text-[11px] font-medium uppercase tracking-wider mb-2">
                    Koleksi Hijab
                </span>
                <h3 class="font-serif text-2xl font-bold">
                    Koleksi Hijab Pilihan
                </h3>
                <p class="text-slate-300 text-xs mt-1 leading-relaxed">
                    Koleksi hijab premium dengan variasi bahan Cradenza Silk, Voal Ultrafine, hingga Pashmina Ceruty. Stok sinkron otomatis dengan sistem gudang.
                </p>
            </div>
            <div class="text-left md:text-right">
                <div class="inline-block p-4 rounded-xl bg-white/5 border border-white/10 text-center">
                    <p class="text-[10px] text-baby-200 uppercase tracking-wider font-semibold">Total Koleksi</p>
                    <p class="text-2xl font-bold font-mono text-white mt-0.5">{{ $products->total() }} Produk</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $categories->count() }} Kategori</p>
                </div>
            </div>
        </div>

        <!-- Filter & Pencarian -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-navy-950 text-sm uppercase tracking-wider">Cari & Saring Koleksi</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Filter berdasarkan nama, bahan, atau kategori produk</p>
                </div>

                <form method="GET" action="{{ route('katalog') }}" class="flex items-center gap-2">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, bahan..."
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 w-60">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs transition">
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'category']))
                        <a href="{{ route('katalog') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Kategori Pills -->
            <div class="flex items-center gap-2 flex-wrap pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-400 text-[11px] font-medium mr-1">Kategori:</span>
                <a href="{{ route('katalog', request()->only('search')) }}"
                    class="px-3 py-1.5 rounded-xl font-medium transition {{ !request('category') ? 'bg-navy-950 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('katalog', array_merge(request()->only('search'), ['category' => $cat->id])) }}"
                        class="px-3 py-1.5 rounded-xl font-medium transition {{ request('category') == $cat->id ? 'bg-navy-950 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Grid Produk Hijab -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($products as $p)
                <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    
                    <div>
                        <!-- Thumbnail Hijab -->
                        <div class="h-44 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                            @if($p->image && file_exists(public_path($p->image)))
                                <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                            
                            <!-- Badge Kategori -->
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded bg-white/90 backdrop-blur-md text-[10px] font-semibold text-navy-950 tracking-wider uppercase border border-slate-200">
                                {{ $p->category->name }}
                            </span>

                            <!-- Badge Status Stok -->
                            @if($p->stock <= 0)
                                <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded bg-rose-600 text-white text-[10px] font-semibold tracking-wider uppercase shadow-sm">
                                    Habis
                                </span>
                            @elseif($p->isLowStock())
                                <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded bg-amber-500 text-white text-[10px] font-semibold tracking-wider uppercase shadow-sm">
                                    Sisa {{ $p->stock }} pcs
                                </span>
                            @else
                                <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-semibold tracking-wider uppercase shadow-sm">
                                    Stok: {{ $p->stock }} pcs
                                </span>
                            @endif
                        </div>

                        <!-- Detail Info -->
                        <div class="p-4">
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 mb-1">
                                <span class="font-mono">SKU: {{ $p->sku }}</span>
                                <span>&bull;</span>
                                <span class="text-baby-700 font-medium">{{ $p->color ?? '-' }}</span>
                            </div>

                            <h4 class="font-bold text-navy-950 text-xs truncate" title="{{ $p->name }}">
                                {{ $p->name }}
                            </h4>

                            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">
                                Bahan: {{ $p->material ?? 'Katun' }}. {{ $p->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Kartu & Tombol Aksi -->
                    <div class="p-4 pt-0">
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between mb-3">
                            <div>
                                <span class="text-[10px] text-slate-400 block leading-none">Harga Satuan</span>
                                <span class="font-bold text-navy-950 font-mono text-sm">
                                    Rp {{ number_format($p->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400 text-right">
                                {{ $p->category->name }}
                            </span>
                        </div>

                        @if(auth()->user()->isCustomer())
                            @if($p->stock > 0)
                                <button type="button" 
                                    onclick="openOrderModal('{{ $p->id }}', '{{ addslashes($p->name) }}', '{{ $p->price }}', '{{ $p->stock }}', '{{ $p->sku }}', '{{ $p->category->name }}')"
                                    class="w-full py-2 px-3 rounded-xl bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs transition shadow-sm flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Pesan Sekarang</span>
                                </button>
                            @else
                                <button type="button" disabled
                                    class="w-full py-2 px-3 rounded-xl bg-slate-100 text-slate-400 font-semibold text-xs cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        @else
                            <div class="text-center py-1.5 bg-slate-50 rounded-xl text-[11px] text-slate-500">
                                Mode Akses: {{ ucfirst(auth()->user()->role) }}
                            </div>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-4 text-center py-16 bg-white rounded-2xl border border-slate-200/80 p-8">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h4 class="font-serif text-base font-bold text-navy-950">Produk Tidak Ditemukan</h4>
                    <p class="text-slate-400 text-xs mt-1">Coba sesuaikan kata kunci pencarian atau pilih kategori lain.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                {{ $products->links() }}
            </div>
        @endif

    </div>

    <!-- Modal Pemesanan Mandiri Customer (Self-Checkout) -->
    <div id="orderModal" class="fixed inset-0 z-50 bg-navy-950/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200 animate-in fade-in zoom-in duration-150">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-baby-100 text-baby-700 flex items-center justify-center font-bold text-xs">
                        D
                    </span>
                    <h3 class="font-serif text-base font-bold text-navy-950">Konfirmasi Pemesanan</h3>
                </div>
                <button type="button" onclick="closeOrderModal()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm">
                    &times;
                </button>
            </div>

            <form action="{{ route('customer.checkout') }}" method="POST" id="customerCheckoutForm">
                @csrf
                <input type="hidden" name="product_id[]" id="modalProductId">

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 mb-4 space-y-1">
                    <span class="text-[10px] font-semibold text-baby-700 uppercase tracking-wider" id="modalCategory">Kategori</span>
                    <h4 class="font-bold text-navy-950 text-sm" id="modalProductName">Nama Produk</h4>
                    <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                        <span>SKU: <span class="font-mono" id="modalProductSku"></span></span>
                        <span class="text-slate-500">Tersedia: <strong class="text-navy-950" id="modalProductStock"></strong> pcs</span>
                    </div>
                </div>

                <!-- Kuantitas Pembelian -->
                <div class="mb-4">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Jumlah Beli (Qty) <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustQty(-1)"
                            class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold flex items-center justify-center">
                            -
                        </button>
                        <input type="number" id="modalQtyInput" name="quantity[]" value="1" min="1"
                            oninput="updateModalTotal()"
                            class="w-24 text-center px-3 py-2 rounded-xl border border-slate-200 text-sm font-bold font-mono text-navy-950 focus:outline-none focus:ring-2 focus:ring-baby-400">
                        <button type="button" onclick="adjustQty(1)"
                            class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold flex items-center justify-center">
                            +
                        </button>
                        <span class="text-xs text-slate-400">Pcs</span>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-4">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Metode Pembayaran <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="border border-slate-200 rounded-xl p-2.5 text-center cursor-pointer hover:border-baby-600 transition has-[:checked]:border-baby-600 has-[:checked]:bg-baby-50">
                            <input type="radio" name="payment_method" value="transfer" class="sr-only" checked>
                            <span class="block text-xs font-semibold text-slate-700">Transfer</span>
                            <span class="text-[10px] text-slate-400 block">Bank BCA/Mandiri</span>
                        </label>

                        <label class="border border-slate-200 rounded-xl p-2.5 text-center cursor-pointer hover:border-baby-600 transition has-[:checked]:border-baby-600 has-[:checked]:bg-baby-50">
                            <input type="radio" name="payment_method" value="qris" class="sr-only">
                            <span class="block text-xs font-semibold text-slate-700">QRIS</span>
                            <span class="text-[10px] text-slate-400 block">GoPay/Shopee</span>
                        </label>

                        <label class="border border-slate-200 rounded-xl p-2.5 text-center cursor-pointer hover:border-baby-600 transition has-[:checked]:border-baby-600 has-[:checked]:bg-baby-50">
                            <input type="radio" name="payment_method" value="cash" class="sr-only">
                            <span class="block text-xs font-semibold text-slate-700">Tunai</span>
                            <span class="text-[10px] text-slate-400 block">Bayar di Toko</span>
                        </label>
                    </div>
                </div>

                <!-- Catatan / Alamat Pengiriman -->
                <div class="mb-5">
                    <label for="modalNotes" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Catatan / Alamat Kirim
                    </label>
                    <textarea id="modalNotes" name="notes" rows="2" placeholder="Contoh: Kirim ke alamat rumah Jl. Flamboyan No. 5"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-baby-400">{{ auth()->user()->address ?? '' }}</textarea>
                </div>

                <!-- Subtotal & Submit -->
                <div class="p-3.5 rounded-xl bg-baby-50 border border-baby-100 flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-600">Total Pembayaran:</span>
                    <span class="font-mono font-bold text-navy-950 text-base" id="modalSubtotalText">Rp 0</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="closeOrderModal()"
                        class="py-2.5 px-4 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="py-2.5 px-4 rounded-xl bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs shadow-sm transition">
                        Konfirmasi Pesan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Script Pengendali Modal Checkout -->
    <script>
        let currentPrice = 0;
        let currentStock = 0;

        function openOrderModal(id, name, price, stock, sku, category) {
            currentPrice = parseFloat(price);
            currentStock = parseInt(stock);

            document.getElementById('modalProductId').value = id;
            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalCategory').textContent = category;
            document.getElementById('modalProductSku').textContent = sku;
            document.getElementById('modalProductStock').textContent = stock;

            const qtyInput = document.getElementById('modalQtyInput');
            qtyInput.value = 1;
            qtyInput.max = currentStock;

            updateModalTotal();
            document.getElementById('orderModal').classList.remove('hidden');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        function adjustQty(delta) {
            const qtyInput = document.getElementById('modalQtyInput');
            let val = parseInt(qtyInput.value) || 1;
            val += delta;
            if (val < 1) val = 1;
            if (val > currentStock) val = currentStock;
            qtyInput.value = val;
            updateModalTotal();
        }

        function updateModalTotal() {
            const qtyInput = document.getElementById('modalQtyInput');
            let val = parseInt(qtyInput.value) || 1;
            if (val < 1) val = 1;
            if (val > currentStock) val = currentStock;

            const total = val * currentPrice;
            document.getElementById('modalSubtotalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
    @endsection

@else
    {{-- Tampilan untuk Tamu Publik (Belum Login) --}}
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Katalog Publik - Dezzana Hijab</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
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
                            navy: { 800: '#0F1E36', 900: '#0A1526', 950: '#060D18' },
                            baby: { 50: '#F0F7FF', 100: '#E0F0FE', 200: '#BAE0FD', 300: '#7DC4FC', 600: '#026BC7', 700: '#0355A0' }
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="bg-slate-50 font-sans min-h-screen text-slate-800">
        <!-- Top Bar Publik -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-navy-950 text-white flex items-center justify-center font-serif text-lg font-bold">D</span>
                <div>
                    <h1 class="font-serif text-base font-bold text-navy-950 leading-none">Dezzana Hijab</h1>
                    <span class="text-[10px] text-baby-700 font-semibold tracking-widest uppercase">Hijab Collection</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('register') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Daftar Pelanggan
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-navy-950 hover:bg-baby-700 text-white text-xs font-semibold transition">
                    Masuk ke Sistem &rarr;
                </a>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-6 py-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-navy-950">Koleksi Dezzana Hijab</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Silakan masuk atau daftar akun pelanggan untuk melakukan pemesanan.</p>
                </div>

                <form method="GET" action="{{ route('katalog') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari hijab..."
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-baby-400 bg-white w-56">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-navy-950 text-white font-semibold text-xs transition">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Kategori Filter -->
            <div class="flex items-center gap-2 flex-wrap mb-6 text-xs">
                <span class="text-slate-400 text-[11px] font-medium mr-1">Kategori:</span>
                <a href="{{ route('katalog', request()->only('search')) }}"
                    class="px-3 py-1.5 rounded-xl font-medium transition {{ !request('category') ? 'bg-navy-950 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('katalog', array_merge(request()->only('search'), ['category' => $cat->id])) }}"
                        class="px-3 py-1.5 rounded-xl font-medium transition {{ request('category') == $cat->id ? 'bg-navy-950 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </a>
                @endforeach
            </div>

            <!-- Grid Koleksi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($products as $p)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="h-40 bg-slate-100 rounded-xl flex items-center justify-center mb-3 overflow-hidden relative">
                                @if($p->image && file_exists(public_path($p->image)))
                                    <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                                <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-semibold">
                                    Stok: {{ $p->stock }} pcs
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold">{{ $p->category->name }}</span>
                            <h4 class="font-bold text-navy-950 text-xs mt-1.5 truncate">{{ $p->name }}</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Bahan: {{ $p->material ?? '-' }}</p>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-bold text-navy-950 font-mono">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                            <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg bg-baby-50 text-baby-700 hover:bg-baby-100 font-semibold text-[11px] transition">
                                Pesan &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Guest -->
            @if($products->hasPages())
                <div class="mt-8 bg-white rounded-2xl border border-slate-200 p-4">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </body>
    </html>
@endif

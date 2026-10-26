<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - Dezzana Hijab</title>
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
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-slate-800">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8 sm:p-10">
        
        <!-- Header Dezzana Hijab -->
        <div class="text-center mb-6">
            <div class="inline-flex w-12 h-12 rounded-xl bg-navy-950 text-white items-center justify-center font-serif text-xl font-bold mb-3 shadow-sm">
                <span class="text-baby-300">D</span>
            </div>
            <h1 class="font-serif text-2xl font-bold text-navy-950 tracking-wide">
                Dezzana Hijab
            </h1>
        </div>

        <!-- Pesan Notifikasi Sukses / Gagal -->
        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login Utama -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Alamat Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="nama@dezzanahijab.com"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition text-xs font-medium">
            </div>

            <div>
                <label for="password" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        placeholder="••••••••"
                        class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-baby-400 focus:border-baby-600 transition text-xs font-medium">
                    <button type="button" onclick="togglePassword('password', 'eyeIcon')" title="Lihat / Sembunyikan Kata Sandi"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                        <span id="eyeIcon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </span>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1 text-xs">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-baby-600 border-slate-300 focus:ring-baby-400">
                    <span class="text-slate-600 text-xs">Ingat saya</span>
                </label>
                <a href="{{ route('katalog') }}" class="text-baby-600 hover:text-baby-700 font-medium hover:underline text-xs">
                    Katalog Publik &rarr;
                </a>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-navy-950 hover:bg-baby-700 text-white font-semibold text-xs shadow-sm transition-all duration-200 flex items-center justify-center gap-2 mt-2">
                <span>Masuk ke Sistem</span>
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
            Belum memiliki akun pelanggan?
            <a href="{{ route('register') }}" class="font-semibold text-baby-700 hover:text-baby-800 hover:underline">
                Daftar di sini
            </a>
        </div>

        <div class="mt-3 text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} Dezzana Hijab
        </div>

    </div>

    <!-- Script Pengendali Kata Sandi -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<svg class="w-4 h-4 text-baby-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
            }
        }
    </script>

</body>
</html>

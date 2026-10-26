<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $request->user()->role;

        // Normalisasi alias 'pelanggan' dan 'customer'
        $normalizedRoles = [];
        foreach ($roles as $r) {
            $normalizedRoles[] = $r;
            if ($r === 'customer') $normalizedRoles[] = 'pelanggan';
            if ($r === 'pelanggan') $normalizedRoles[] = 'customer';
        }

        // 2. Cek apakah role user ada di dalam daftar role yang diizinkan
        if (!in_array($userRole, $normalizedRoles)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}

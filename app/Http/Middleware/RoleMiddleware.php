<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->has('iduser')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }

        // Mengambil role pengguna
        $namaRole = session('nama_role');

        // Memeriksa apakah role memiliki izin
        if (!in_array($namaRole, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Facade Auth
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah pengguna sudah login DAN apakah is_admin == true
        if (Auth::check() && Auth::user()->is_admin) {
            // Jika ya, lanjutkan ke request berikutnya (halaman admin)
            return $next($request);
        }

        // 2. Jika tidak, redirect ke halaman utama dengan pesan error
        //    Anda bisa juga redirect ke halaman login atau halaman lain
        return redirect('/')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.'); 
        
        // Atau, jika Anda ingin menampilkan halaman 403 Forbidden:
        // abort(403, 'Unauthorized action.');
    }
}
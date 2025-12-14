<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// === TAMBAHKAN USE STATEMENT INI ===
use App\Http\Middleware\EnsureUserIsAdmin; 
// === AKHIR TAMBAHAN ===

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // === TAMBAHKAN BARIS INI UNTUK ALIAS ===
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class, // 'admin' adalah nama aliasnya
        ]);
        // === AKHIR TAMBAHAN ===

        // Middleware lain mungkin sudah ada di sini, biarkan saja
        // $middleware->web(append: [ ... ]);
        // $middleware->api(prepend: [ ... ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
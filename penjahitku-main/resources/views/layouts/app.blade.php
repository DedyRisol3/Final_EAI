<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- CSRF Token --}}
    <title>{{ $title ?? config('app.name', 'PenjahitKu') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Slot untuk CSS tambahan per halaman --}}
    {{ $styles ?? '' }}
</head>
<body class="antialiased"> {{-- Body sudah di-style oleh app.css --}}

    <header class="header-nav">
        <div class="container">
            <a href="{{ url('/') }}" class="logo">PenjahitKu</a>
            <div class="right-nav">
                {{-- Link Keranjang dengan Counter awal 0 (diisi JS) --}}
                <a href="{{ route('keranjang.index') }}" class="cart-link">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                    (<span id="cart-count-header">0</span>) {{-- Angka awal 0 --}}
                </a>

                 {{-- Autentikasi --}}
                 @if (Route::has('login'))
                    {{-- Tampilan GUEST --}}
                    @guest
                        <div class="auth-links" id="guest-links">
                            <a href="{{ route('login') }}" class="auth-login">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="auth-register">Daftar</a>
                            @endif {{-- Penutup @if register --}}
                        </div>
                    @endguest

                    {{-- Tampilan LOGGED IN --}}
                    @auth
                         <div class="user-greeting" id="user-links">
                            <span>Halo, <strong id="user-name">{{ explode(' ', Auth::user()->name)[0] }}</strong>!</span>
                             @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="admin-link ml-4">Admin Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;" class="ml-4">
                                @csrf
                                <a href="{{ route('logout') }}" class="logout-btn"
                                   onclick="event.preventDefault(); this.closest('form').submit();" id="logout-btn">Logout</a>
                            </form>
                        </div>
                    @endauth
                 @endif {{-- Penutup @if login --}}
            </div>
        </div>
    </header>

    {{-- Konten Utama dari Halaman Spesifik --}}
        <main>
            {{ $slot }}
        </main>

    {{-- Footer Kustom Kita --}}
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} PenjahitKu. Dibuat dengan Laravel.</p>
        </div>
    </footer>

    {{-- Script dasar (pindah ke app.js nanti lebih baik) --}}
    <script>
        // Fungsi global untuk update counter header
         function updateCartCounter(totalItems) {
            const cartCountEl = document.getElementById('cart-count-header');
            // Pastikan totalItems adalah angka sebelum ditampilkan
            const count = Number.isInteger(totalItems) ? totalItems : 0;
            if(cartCountEl) cartCountEl.textContent = count;
         }

         document.addEventListener('DOMContentLoaded', function() {
            // Ambil data keranjang dari backend saat halaman load
            fetch("{{ route('keranjang.data') }}") // Panggil route API keranjang
                .then(response => {
                    if (!response.ok) {
                        // Jangan lempar error, cukup log dan set counter 0
                        console.error('Gagal mengambil data keranjang:', response.statusText);
                        return { totalItems: 0 }; // Return object default
                    }
                     // Periksa content type sebelum parse JSON
                     const contentType = response.headers.get("content-type");
                     if (contentType && contentType.indexOf("application/json") !== -1) {
                         return response.json();
                     } else {
                         // Jika bukan JSON, mungkin halaman login atau error lain
                         console.error('Response bukan JSON saat fetch cart data.');
                         return { totalItems: 0 }; // Return object default
                     }
                })
                .then(data => {
                    // Update counter header dengan data dari backend
                    updateCartCounter(data.totalItems);
                })
                .catch(error => {
                    console.error("Error fetching cart data:", error);
                    // Biarkan counter 0 jika gagal load
                    updateCartCounter(0);
                });
        });
    </script>

    {{-- Slot untuk JavaScript tambahan per halaman --}}
    {{ $scripts ?? '' }}
</body>
</html>
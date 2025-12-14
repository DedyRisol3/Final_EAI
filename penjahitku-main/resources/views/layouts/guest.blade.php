<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased"> <header class="header-nav">
        <div class="container">
            <a href="{{ url('/') }}" class="logo">PenjahitKu</a>
            
            <div class="right-nav">
                @if (Route::has('login'))
                    <div class="auth-links">
                        @auth
                            <div class="user-greeting">
                                <span>Halo, <strong>{{ explode(' ', Auth::user()->name)[0] }}</strong>!</span>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="auth-login">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="auth-register">Daftar</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <main>
        <div class="min-h-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="padding: 4rem 0;">
            
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} PenjahitKu. Dibuat dengan Laravel.</p>
        </div>
    </footer>
</body>
</html>
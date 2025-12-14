<x-guest-layout> {{-- Menggunakan layout guest.blade.php --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Tampilkan pesan error (termasuk dari Google redirect) --}}
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-400 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif
    {{-- Tampilkan juga error validasi Breeze --}}
     <x-input-error :messages="$errors->get('email')" class="mb-4" />
     <x-input-error :messages="$errors->get('password')" class="mb-4" />


     <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            {{-- Error dipindah ke atas --}}
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
             {{-- Error dipindah ke atas --}}
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        {{-- === TOMBOL LOGIN GOOGLE (TAMBAHKAN KEMBALI) === --}}
        <div class="my-4 flex items-center before:flex-1 before:border-t before:border-gray-300 before:mt-0.5 after:flex-1 after:border-t after:border-gray-300 after:mt-0.5">
             <p class="text-center font-semibold mx-4 mb-0 text-sm text-gray-500">Atau</p>
        </div>
        <a href="{{ route('google.redirect') }}"
           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="h-5 w-5 mr-2">
            Login dengan Google
        </a>
        {{-- === AKHIR TOMBOL LOGIN GOOGLE === --}}

    </form>
</x-guest-layout>
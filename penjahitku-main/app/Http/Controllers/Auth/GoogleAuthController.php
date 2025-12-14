<?php

namespace App\Http\Controllers\Auth; // Ensure correct namespace

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Optional for random password generation
use Illuminate\Support\Facades\Log; // For logging errors
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Exception;
use Laravel\Socialite\Facades\Socialite; // Ensure this is imported

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(Request $request): RedirectResponse
    {
        $this->applyLoyaltyCallback($request);

        // === THIS IS THE CHECK THAT'S FAILING ===
        // Verify config values are loaded before redirecting
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
             Log::error('Google client ID or Secret not configured.'); // Logs the error
             // Redirect back to login with a user-friendly error message
             return redirect('/login')->with('error', 'Konfigurasi login Google belum lengkap.');
        }
        // === END CHECK ===

        // If config is okay, redirect to Google
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
             Log::error('Google Redirect Error: '.$e->getMessage());
             return redirect('/login')->with('error', 'Gagal mengalihkan ke Google. Periksa konfigurasi.');
        }
    }

    /**
     * Obtain the user information from Google and handle login/registration.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(): RedirectResponse
    {
        // ... (Callback logic remains the same as before) ...
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Auth::login($user, true);
            } else {
                $user = User::where('email', $googleUser->getEmail())->first();
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                    Auth::login($user, true);
                } else {
                    $newUser = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => null,
                        'is_admin' => false,
                        'email_verified_at' => now(),
                    ]);
                    Auth::login($newUser, true);
                }
            }
            return redirect()->intended('/');

        } catch (Exception $e) {
            Log::error('Google Login Callback Error: '.$e->getMessage());
            $errorMessage = 'Gagal login dengan Google. Silakan coba lagi.';
            if ($e instanceof \Laravel\Socialite\Two\InvalidStateException) {
                $errorMessage = 'Sesi login Google tidak valid. Silakan coba lagi.';
            } elseif (str_contains($e->getMessage(), 'invalid_grant')) {
                $errorMessage = 'Otorisasi Google gagal. Silakan coba lagi.';
            }
            return redirect('/login')->with('error', $errorMessage);
        }
    }

    protected function applyLoyaltyCallback(Request $request): void
    {
        $callback = $request->query('callback');

        if (!$this->isAllowedLoyaltyCallback($callback)) {
            return;
        }

        session(['loyalty.callback' => $callback]);
        session()->put('url.intended', route('loyalty.authorize', ['callback' => $callback]));
    }

    protected function isAllowedLoyaltyCallback(?string $callback): bool
    {
        $base = config('services.loyalty.base_url');

        if (!$callback || !$base) {
            return false;
        }

        return Str::startsWith($callback, $base);
    }
}

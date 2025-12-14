<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoyaltyAuthController extends Controller
{
    public function authorize(Request $request)
    {
        $callback = $request->input('callback') ?? session('loyalty.callback');

        if (!$callback || !$this->isAllowedCallback($callback)) {
            abort(403, 'Callback loyalty tidak valid atau belum diatur.');
        }

        session(['loyalty.callback' => $callback]);
        session()->put('url.intended', $request->fullUrl());

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $this->redirectWithToken($callback);
    }

    protected function redirectWithToken(string $callback)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('loyalty_app_token')->plainTextToken;
        session()->forget('loyalty.callback');

        $separator = Str::contains($callback, '?') ? '&' : '?';
        return redirect()->away($callback . $separator . 'token=' . $token);
    }

    protected function isAllowedCallback(string $callback): bool
    {
        $base = config('services.loyalty.base_url');

        if (!$base) {
            return false;
        }

        return Str::startsWith($callback, $base);
    }
}

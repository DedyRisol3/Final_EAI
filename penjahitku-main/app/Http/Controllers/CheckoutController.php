<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Xendit\Xendit;
use Exception;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong.');
        }

        $totalPrice = collect($cartItems)->sum(
            fn($item) => ((int)($item['quantity'] ?? 0)) * ((float)($item['priceRaw'] ?? 0))
        );

        // Mengambil data provinsi untuk dropdown RajaOngkir
        $provinces = [];
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'key' => config('rajaongkir.api_key'),
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        if ($response->successful()) {
            $provinces = $response->json()['data'] ?? [];
        }

        $loyalty = app(\App\Services\LoyaltyClient::class);
        $balanceResponse = $loyalty->getPointBalance(Auth::user()->id);

        $pointBalance = $balanceResponse['total_points'] ?? 0;

        return view('checkout', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'provinces' => $provinces,
            'pointBalance' => $pointBalance
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|string', // 'xendit'

            // Validasi data pengiriman baru
            'province_id' => 'required|string',
            'city_id' => 'required|string',
            'district_id' => 'required|string',
            'courier' => 'required|string', //
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_etd' => 'nullable|string',
        ]);

        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = collect($cartItems)->sum(
            fn($item) => ((int)($item['quantity'] ?? 0)) * ((float)($item['priceRaw'] ?? 0))
        );

        $order = new Order();
        $order->user_id = Auth::id();

        // Data pelanggan
        $order->name = $validatedData['name'];
        $order->email = $validatedData['email'];
        $order->phone = $validatedData['phone'];
        $order->payment_method = $validatedData['payment_method'];

        // Data pengiriman RajaOngkir
        $order->province_id = $validatedData['province_id'];
        $order->city_id = $validatedData['city_id'];
        $order->district_id = $validatedData['district_id'];
        $order->courier = $validatedData['courier'];
        $order->shipping_cost = $validatedData['shipping_cost'];
        $order->shipping_etd = $validatedData['shipping_etd'] ?? null;

        $discountValue = 0;

        if ($request->use_points && $request->points_used > 0) {
            $loyalty = app(\App\Services\LoyaltyClient::class);

            $redeemResult = $loyalty->redeemPoints(Auth::id(), $request->points_used, $order->id);

            if (!($redeemResult['success'] ?? false)) {
                return back()->with('error', $redeemResult['message'] ?? 'Redeem poin gagal.');
            }

            $discountValue = $redeemResult['discount_value'] ?? 0;
        }


        // Total harga adalah Subtotal
        $order->total_price = ($subtotal + $validatedData['shipping_cost']) - $discountValue;
        $order->discount_value = $discountValue;

        $order->status = 'pending'; // Akan di-update ke 'waiting_payment' di bawah
        $order->save();
        // Simpan item-item pesanan
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'] ?? null,
                'product_name' => $item['name'] ?? 'Produk Tidak Diketahui',
                'quantity' => $item['quantity'] ?? 0,
                'price' => $item['priceRaw'] ?? 0,
                'custom_size' => $item['custom']['ukuran'] ?? null,
                'custom_chest' => $item['custom']['lingkarDada'] ?? null,
                'custom_notes' => $item['custom']['catatan'] ?? null,
            ]);
        }

        session()->forget('cart');

        // Gunakan API Key dari .env
        Xendit::setApiKey(config('services.xendit.secret_key'));

        // Buat invoice baru
        $params = [
            'external_id' => 'order-' . $order->id,
            'payer_email' => $order->email,
            'description' => 'Pembayaran Pesanan #' . $order->id,
            'amount' => $order->total_price,
            'success_redirect_url' => route('checkout.success'),
            'failure_redirect_url' => route('checkout.failed'),
        ];

        try {
            $invoice = \Xendit\Invoice::create($params);

            // Update pesanan dengan URL pembayaran
            $order->update([
                'payment_url' => $invoice['invoice_url'],
                'status' => 'waiting_payment',
            ]);

            session()->flash('lastOrder', [
                'orderId' => $order->id,
                'customerName' => $order->name,
                'totalPrice' => $order->total_price,
            ]);

            return redirect()->away($invoice['invoice_url']);
        } catch (Exception $e) {
            return redirect()->route('checkout.index')->with('error', 'Gagal membuat invoice Xendit: ' . $e->getMessage());
        }
    }

    public function checkBalance()
    {
        Xendit::setApiKey(config('services.xendit.secret_key'));

        try {
            $balance = \Xendit\Balance::getBalance('CASH');
            return response()->json($balance);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

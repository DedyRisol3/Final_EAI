<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index(): View
    {
        $cartItems = session('cart', []);
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 0;
            $priceRaw = isset($item['priceRaw']) ? (float)$item['priceRaw'] : 0;
            $totalPrice += $quantity * $priceRaw;
        }
        return view('keranjang', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }

    /**
     * Mengambil data keranjang saat ini (untuk AJAX).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // === TAMBAHKAN METHOD BARU INI ===
    public function data(): JsonResponse
    {
        $cart = session('cart', []);
        $totalItems = 0;
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalItems += (int)($item['quantity'] ?? 0);
            $totalPrice += (float)($item['priceRaw'] ?? 0) * (int)($item['quantity'] ?? 0);
        }

        return response()->json([
            'cart' => $cart, // Kirim detail keranjang (opsional)
            'totalItems' => $totalItems,
            'totalPrice' => $totalPrice,
        ]);
    }
    // === AKHIR METHOD BARU ===

    /**
     * Menambahkan item ke keranjang (Session) atau menggabungkannya jika sudah ada.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_name' => 'required|string',
            'price_raw' => 'required|numeric',
            'image_url' => 'nullable|string',
            'custom_size' => 'nullable|string',
            'custom_chest' => 'nullable|string',
            'custom_notes' => 'nullable|string',
        ]);

        $cart = session('cart', []);
        $foundKey = null;
        foreach ($cart as $key => $existingItem) {
            $existingCustom = $existingItem['custom'] ?? [];
            $newCustomSize = $validatedData['custom_size'] ?? 'Standar';
            $newCustomChest = $validatedData['custom_chest'] ?? '-';
            $newCustomNotes = $validatedData['custom_notes'] ?? '-';
            $idMatch = ($existingItem['id'] ?? null) == $validatedData['product_id'];
            $customsMatch = (
                ($existingCustom['ukuran'] ?? 'Standar') == $newCustomSize &&
                ($existingCustom['lingkarDada'] ?? '-') == $newCustomChest &&
                ($existingCustom['catatan'] ?? '-') == $newCustomNotes
            );
            if ($idMatch && $customsMatch) {
                $foundKey = $key;
                break;
            }
        }

        if ($foundKey !== null) {
            $cart[$foundKey]['quantity'] += (int)$validatedData['quantity'];
            $message = 'Jumlah ' . $validatedData['product_name'] . ' di keranjang diperbarui.';
        } else {
            $cartItem = [
                'cartId' => uniqid('item_'),
                'id' => $validatedData['product_id'],
                'name' => $validatedData['product_name'],
                'priceDisplay' => "Rp " . number_format($validatedData['price_raw'], 0, ',', '.'),
                'priceRaw' => (float)$validatedData['price_raw'],
                'imageUrl' => $validatedData['image_url'],
                'quantity' => (int)$validatedData['quantity'],
                'custom' => [
                    'ukuran' => $validatedData['custom_size'] ?? 'Standar',
                    'lingkarDada' => $validatedData['custom_chest'] ?? '-',
                    'catatan' => $validatedData['custom_notes'] ?? '-',
                ]
            ];
            $cart[$cartItem['cartId']] = $cartItem;
            $message = $validatedData['product_name'] . ' berhasil ditambahkan!';
        }

        session(['cart' => $cart]);

        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += (int)($item['quantity'] ?? 0);
        }

        return response()->json([
            'message' => $message,
            'totalItems' => $totalItems,
        ]);
    }


    /**
     * Mengupdate jumlah item di keranjang (Session).
     */
    public function update(Request $request, string $cartId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $newQuantity = (int)$validated['quantity'];
        $cart = session('cart', []);

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] = $newQuantity;
            session(['cart' => $cart]);
            $totalItems = 0; $totalPrice = 0;
            foreach ($cart as $item) {
                $totalItems += (int)($item['quantity'] ?? 0);
                $totalPrice += (float)($item['priceRaw'] ?? 0) * (int)($item['quantity'] ?? 0);
            }
            return response()->json([
                'message' => 'Jumlah item berhasil diupdate.',
                'totalItems' => $totalItems,
                'totalPrice' => $totalPrice,
                'itemSubtotal' => (float)($cart[$cartId]['priceRaw'] ?? 0) * $newQuantity
            ]);
        }
        return response()->json(['message' => 'Item tidak ditemukan di keranjang.'], 404);
    }

    /**
     * Menghapus item dari keranjang (Session).
     */
     public function destroy(string $cartId): JsonResponse
     {
        $cart = session('cart', []);
        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
            session(['cart' => $cart]);
            $totalItems = 0; $totalPrice = 0;
            foreach ($cart as $item) {
                $totalItems += (int)($item['quantity'] ?? 0);
                $totalPrice += (float)($item['priceRaw'] ?? 0) * (int)($item['quantity'] ?? 0);
            }
            return response()->json([
                'message' => 'Item berhasil dihapus dari keranjang.',
                'totalItems' => $totalItems,
                'totalPrice' => $totalPrice,
            ]);
        }
        return response()->json(['message' => 'Item tidak ditemukan di keranjang.'], 404);
     }
}
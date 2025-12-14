<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\LoyaltyApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $orders = Order::latest()->paginate(15);
        return view('admin.pesanan.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $orderId): View
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('admin.pesanan.show', compact('order'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $orderId): View
    {
        // Redirect saja ke halaman show
        return redirect()->route('admin.pesanan.show', ['orderId' => $orderId]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $orderId, LoyaltyApiService $loyalty): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,cancelled',
        ]);
        $order = Order::findOrFail($orderId);
        $order->status = $validated['status'];
        $order->save();

        if ($order->status == 'paid') {
            // Kirim poin ke Loyalty Service
            $response = $loyalty->grantPoints($order->user_id, $order->id);
            dd($response);

            // Cek response loyalty service
            if ($response['status'] === 'success') {
                return back()->with('success', 'Pembayaran dikonfirmasi dan poin berhasil diberikan.');
            }

            if ($response['status'] === 'duplicate') {
                return back()->with('warning', 'Pembayaran dikonfirmasi. Poin sudah pernah diberikan sebelumnya.');
            }
        }

        return redirect()->route('admin.pesanan.show', ['orderId' => $orderId])
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus pesanan dari database.
     */
    // === PASTIKAN METHOD INI BENAR ===
    public function destroy(string $orderId): RedirectResponse // Terima $orderId
    {
        // 1. Cari order berdasarkan ID
        $order = Order::findOrFail($orderId);

        // 2. Hapus pesanan (dan item-itemnya karena onDelete('cascade') di migration item)
        try {
            $order->delete();
            // 3. Redirect kembali ke daftar pesanan dengan pesan sukses
            return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika gagal menghapus
            \Log::error('Gagal menghapus pesanan: ' . $e->getMessage());
            return redirect()->route('admin.pesanan.index')->with('error', 'Gagal menghapus pesanan.');
        }
    }
    // === AKHIR METHOD DESTROY ===
}

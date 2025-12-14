<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace Admin

use App\Http\Controllers\Controller;
use App\Models\Order; // Import Order
use App\Models\Product; // Import Product
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View
use Illuminate\Support\Facades\DB; // Import DB Facade untuk count

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data ringkasan.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Hitung jumlah total produk
        $totalProducts = Product::count();

        // Hitung jumlah pesanan berdasarkan status
        $orderCounts = Order::select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->pluck('total', 'status'); // Hasilnya: ['pending' => 5, 'paid' => 2, ...]

        // Siapkan data untuk dikirim ke view
        $summaryData = [
            'totalProducts' => $totalProducts,
            'pendingOrders' => $orderCounts->get('pending', 0), // Ambil count 'pending', default 0
            'paidOrders' => $orderCounts->get('paid', 0),       // Ambil count 'paid', default 0
            'shippedOrders' => $orderCounts->get('shipped', 0), // Ambil count 'shipped', default 0
             // Tambahkan status lain jika perlu
        ];

        // Kirim data ke view 'admin.dashboard'
        return view('admin.dashboard', compact('summaryData'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\View\View; // Pastikan ini ada

class ProductController extends Controller
{
    /**
     * Menampilkan halaman utama (katalog produk).
     */
    public function index(): View
    {
        $products = Product::latest()->get();
        return view('welcome', [
            'products' => $products
        ]);
    }

    /**
     * Menampilkan halaman detail satu produk.
     * @param \App\Models\Product $product Model Product yang ditemukan otomatis oleh Laravel berdasarkan slug di URL
     * @return \Illuminate\View\View
     */
    // === TAMBAHKAN METHOD BARU DI SINI ===
    public function show(Product $product): View
    {
        // Variabel $product sudah berisi data produk yang cocok dengan slug
        // Kirim data $product ke view 'produk.detail'
        return view('produk.detail', [
            'product' => $product
        ]);
    }
    // === AKHIR TAMBAHAN ===
}
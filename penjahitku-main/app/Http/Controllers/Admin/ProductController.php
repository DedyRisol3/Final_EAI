<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(10);
        return view('admin.produk.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $baseSlug = Str::slug($validatedData['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        $validatedData['slug'] = $slug;

        Product::create($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // === PERUBAHAN DEBUGGING DI SINI ===
    // Kita hilangkan Route Model Binding sementara, ambil ID manual
    public function edit($productId): View // Ganti 'Product $product' menjadi '$productId'
    {
        // Coba cari produk secara manual berdasarkan ID dari URL
        $product = Product::find($productId);

        // Debug: Tampilkan apa yang ditemukan (atau tidak ditemukan)
        // dd($productId, $product); // <-- DEBUGGING UTAMA

        // Jika produk tidak ditemukan, tampilkan 404
        if (!$product) {
            abort(404, 'Produk tidak ditemukan.');
        }

        // Jika ditemukan, kirim ke view
        return view('admin.produk.edit', compact('product'));
    }
    // === AKHIR PERUBAHAN DEBUGGING ===

    /**
     * Update the specified resource in storage.
     */
    // Kita perlu ubah ini juga karena parameter $product mungkin tidak otomatis ter-resolve
    public function update(Request $request, $productId): RedirectResponse // Ganti 'Product $product'
    {
         $product = Product::find($productId); // Cari produk manual
         if (!$product) {
            abort(404, 'Produk tidak ditemukan.');
         }

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        if ($request->name !== $product->name) {
            $baseSlug = Str::slug($validatedData['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $validatedData['slug'] = $slug;
        }

        $product->update($validatedData);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
     // Kita perlu ubah ini juga
    public function destroy($productId): RedirectResponse // Ganti 'Product $product'
    {
        $product = Product::find($productId); // Cari produk manual
         if (!$product) {
            // Bisa redirect dengan error atau 404
             return redirect()->route('admin.produk.index')->with('error', 'Produk tidak ditemukan.');
         }

        try {
            $product->delete();
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
             \Log::error('Gagal menghapus produk: '.$e->getMessage());
             return redirect()->route('admin.produk.index')->with('error', 'Gagal menghapus produk.');
        }
    }
}
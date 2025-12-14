<x-app-layout> {{-- Menggunakan layout app.blade.php --}}

    {{-- Judul Halaman Spesifik --}}
    <x-slot name="title">
        {{ $product->name }} - PenjahitKu
    </x-slot>

    {{-- Slot CSS Tambahan --}}
    <x-slot name="styles">
        <style>
            /* Gaya spesifik halaman detail */
            .product-detail-section { padding: 3rem 0; }
            .product-detail-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
            @media (min-width: 768px) { .product-detail-grid { grid-template-columns: 1fr 1fr; } }
            .product-image-gallery img { width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-height: 500px; object-fit: cover; }
            .product-info h1 { font-size: 2.5rem; color: var(--dark-color); margin-bottom: 1rem; }
            .product-info .price { font-size: 1.8rem; font-weight: 700; color: var(--primary-color); margin-bottom: 1.5rem; }
            .product-info .description { font-size: 1.1rem; line-height: 1.7; margin-bottom: 2rem; }
            .measurement-form { background-color: var(--light-color); padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid var(--grey-color); }
            .measurement-form h3 { margin-bottom: 1.5rem; color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; }
            .form-group { margin-bottom: 1rem; }
            .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
            .quantity-group { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
            .quantity-group label { margin-bottom: 0; }
            .quantity-group input { width: 80px; text-align: center;}
            .btn-primary { background-color: var(--primary-color); color: var(--white-color); }
            .btn-primary:hover { background-color: #006a6a; }
            .back-link { display: inline-block; margin-top: 1rem; font-size: 0.95rem; color: var(--primary-color); text-decoration: none; padding: 0.5rem 0; }
            .back-link:hover { text-decoration: underline; }
        </style>
    </x-slot>

    {{-- Konten Utama Halaman Detail --}}
    <div class="product-detail-section">
        <div class="container"> {{-- Container dari layout --}}
            <div class="product-detail-grid">

                {{-- Kolom Gambar --}}
                <div class="product-image-gallery">
                    <img id="product-image"
                         src="{{ $product->image_url ?: 'https://via.placeholder.com/600x500.png?text=No+Image' }}"
                         alt="{{ $product->name }}">
                </div>

                {{-- Kolom Info --}}
                <div class="product-info">
                    <h1 id="product-name">{{ $product->name }}</h1>
                    <div id="product-price" class="price">
                        @if($product->price > 0)
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @else
                            Hubungi untuk Penawaran
                        @endif
                    </div>
                    <p id="product-description" class="description">
                        {{ $product->description ?: 'Deskripsi belum tersedia.' }}
                    </p>

                    {{-- Form Ukuran & Kuantitas --}}
                    <form id="measurement-form" class="measurement-form">
                        <h3>Masukkan Detail Pesanan</h3>
                        <div class="form-group">
                            <label for="ukuran">Ukuran (S/M/L)</label>
                            <input type="text" id="ukuran" placeholder="Contoh: L atau XL" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                        </div>
                        <div class="form-group">
                            <label for="lingkar_dada">Lingkar Dada (cm)</label>
                            <input type="number" id="lingkar_dada" placeholder="Contoh: 98 (Opsional)" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                        </div>
                        <div class="form-group">
                            <label for="catatan">Catatan Tambahan</label>
                            <textarea id="catatan" placeholder="Contoh: Panjang lengan 60cm..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full" rows="3"></textarea>
                        </div>
                        <div class="quantity-group form-group">
                            <label for="quantity">Jumlah:</label>
                            <input type="number" id="quantity" value="1" min="1" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-center">
                        </div>
                        <button type="submit" id="add-to-cart-btn" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                        </button>
                    </form>
                    <a href="{{ route('home') }}" class="back-link"> Kembali ke Katalog</a>
                </div>

            </div>
        </div>
    </div>

    {{-- Slot JavaScript Tambahan --}}
    <x-slot name="scripts">
        <script>
            // === TIDAK ADA FUNGSI 'updateCartCountHeaderBasedOnLocalStorage()' DI SINI ===

            document.addEventListener('DOMContentLoaded', function() {
                // === TIDAK ADA PEMANGGILAN 'updateCartCountHeaderBasedOnLocalStorage()' SAAT LOAD ===

                const measurementForm = document.getElementById('measurement-form');
                const addToCartBtn = document.getElementById('add-to-cart-btn');

                 if(measurementForm && addToCartBtn){
                     measurementForm.addEventListener('submit', function(e) {
                         e.preventDefault();
                         addToCartBtn.disabled = true;
                         addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';

                         const ukuran = document.getElementById('ukuran').value;
                         const lingkarDada = document.getElementById('lingkar_dada').value;
                         const catatan = document.getElementById('catatan').value;
                         const quantity = parseInt(document.getElementById('quantity').value) || 1;
                         const productData = @json($product);

                         const dataToSend = {
                             _token: "{{ csrf_token() }}",
                             product_id: productData.id,
                             quantity: quantity,
                             product_name: productData.name,
                             price_raw: productData.price,
                             image_url: productData.image_url,
                             custom_size: ukuran || null,
                             custom_chest: lingkarDada || null,
                             custom_notes: catatan || null,
                         };

                         fetch("{{ route('keranjang.store') }}", {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'Accept': 'application/json',
                                 'X-Requested-With': 'XMLHttpRequest'
                             },
                             body: JSON.stringify(dataToSend)
                         })
                         .then(response => {
                             if (!response.ok) {
                                 return response.json().then(errorData => {
                                     let errorMsg = errorData.message || 'Terjadi kesalahan.';
                                     if (errorData.errors) {
                                         errorMsg = Object.values(errorData.errors)[0][0];
                                     }
                                     throw new Error(errorMsg);
                                 }).catch(() => {
                                     throw new Error(`HTTP error! status: ${response.status}`);
                                 });
                             }
                             return response.json();
                         })
                         .then(data => {
                             alert(data.message);
                             // Langsung panggil fungsi global 'updateCartCounter'
                             // (yang ada di app.blade.php) dengan data dari backend
                             if(typeof updateCartCounter === 'function' && data.totalItems !== undefined) {
                                 updateCartCounter(data.totalItems);
                             }
                         })
                         .catch(error => {
                             console.error('Error adding to cart:', error);
                             alert('Gagal menambahkan ke keranjang: ' + error.message);
                         })
                         .finally(() => {
                             addToCartBtn.disabled = false;
                             addToCartBtn.innerHTML = '<i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang';
                         });
                     });
                 }
            });
         </script>
    </x-slot>

</x-app-layout>
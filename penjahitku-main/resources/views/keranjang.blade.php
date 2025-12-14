<x-app-layout> {{-- Menggunakan layout app.blade.php --}}

    {{-- Judul Halaman Spesifik --}}
    <x-slot name="title">
        Keranjang Belanja - PenjahitKu
    </x-slot>

    {{-- Slot CSS Tambahan (Gaya keranjang, sebagian besar sudah di app.css) --}}
    <x-slot name="styles">
        <style>
            .cart-section { padding: 3rem 0; }
            .cart-section h1 {
                font-size: 2.5rem;
                color: var(--dark-color);
                margin-bottom: 2rem;
                border-bottom: 2px solid var(--grey-color);
                padding-bottom: 1rem;
            }
            .cart-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
            @media (min-width: 768px) { .cart-layout { grid-template-columns: 2fr 1fr; } }
            #cart-items-list { display: flex; flex-direction: column; gap: 1.5rem; }
            .cart-item-card { display: flex; background: var(--white-color); border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid var(--grey-color); }
            .cart-item-card img { width: 100px; height: 100%; object-fit: cover; } /* height: 100% agar responsif */
            .cart-item-info { padding: 1rem; flex: 1; }
            .cart-item-info h3 { font-size: 1.2rem; color: var(--primary-color); }
            .cart-item-info p { font-size: 1rem; color: #666; margin: 0.25rem 0; }
            .cart-item-details { font-size: 0.9rem; color: #444; background: var(--light-color); padding: 0.5rem; border-radius: 4px; margin-top: 0.5rem; }
            .cart-item-actions { display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; }
            .cart-item-actions .quantity-control { display: flex; align-items: center; gap: 0.5rem; }
            .cart-item-actions label { font-size: 0.9rem; font-weight: 600; }
            /* Style input jumlah menggunakan class Tailwind dari Breeze */
            .cart-item-actions .quantity-input { width: 60px; padding: 0.5rem; text-align: center; }
            .remove-item-btn { background: none; border: none; color: var(--secondary-color); font-size: 0.9rem; font-weight: 600; cursor: pointer; }
            .remove-item-btn:hover { text-decoration: underline; }
            .cart-summary { background: var(--white-color); border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 2rem; border: 1px solid var(--grey-color); position: sticky; top: 100px; }
            .cart-summary h2 { font-size: 1.5rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--grey-color); padding-bottom: 1rem; }
            .summary-row { display: flex; justify-content: space-between; font-size: 1.1rem; margin-bottom: 1rem; }
            .summary-row.total { font-size: 1.3rem; font-weight: 700; color: var(--primary-color); border-top: 2px solid var(--grey-color); padding-top: 1rem; margin-top: 1.5rem; }
            .empty-cart-message { text-align: center; padding: 3rem; background: var(--white-color); border-radius: 8px; }
            .empty-cart-message p { font-size: 1.2rem; margin-bottom: 1.5rem; }
            /* Gunakan style tombol dari app.css */
        </style>
    </x-slot>

    {{-- Konten Utama Halaman Keranjang --}}
    <div class="cart-section">
        <div class="container">
            <h1>Keranjang Belanja Anda</h1>

            {{-- Cek jika keranjang ($cartItems dari Controller) kosong --}}
            @if (isset($cartItems) && count($cartItems) > 0)
                <div class="cart-layout" id="cart-layout">
                    {{-- Kolom 1: Daftar Item --}}
                    <div id="cart-items-list">
                        {{-- Loop data dari Controller --}}
                        @foreach ($cartItems as $cartId => $item)
                            <div class="cart-item-card" data-cart-id="{{ $cartId }}">
                                <img src="{{ $item['imageUrl'] ?? 'https://via.placeholder.com/100x120.png?text=No+Image' }}" alt="{{ $item['name'] }}">
                                <div class="cart-item-info">
                                    <h3>{{ $item['name'] }}</h3>
                                    {{-- Harga per item --}}
                                    <p>{{ $item['priceDisplay'] ?? 'Harga tidak tersedia' }}</p>
                                    <div class="cart-item-details">
                                        {{-- Detail kustomisasi --}}
                                        <strong>Ukuran:</strong> {{ $item['custom']['ukuran'] ?? '-' }} |
                                        <strong>LD:</strong> {{ $item['custom']['lingkarDada'] ?? '-' }} cm |
                                        <strong>Catatan:</strong> {{ $item['custom']['catatan'] ?? '-' }}
                                    </div>
                                    <div class="cart-item-actions">
                                        <div class="quantity-control">
                                            <label for="qty-{{ $cartId }}">Jumlah:</label>
                                            {{-- Input jumlah (Update & Hapus BELUM berfungsi di backend) --}}
                                            <input type="number" class="quantity-input border-gray-300 rounded-md shadow-sm" id="qty-{{ $cartId }}" value="{{ $item['quantity'] ?? 1 }}" min="1" data-id="{{ $cartId }}">
                                        </div>
                                        {{-- Tombol hapus (BELUM berfungsi di backend) --}}
                                        <button class="remove-item-btn" data-id="{{ $cartId }}">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Kolom 2: Ringkasan --}}
                    <div class="cart-summary" id="cart-summary">
                        <h2>Ringkasan Pesanan</h2>
                        <div class="summary-row">
                            {{-- Hitung total item dari data Controller --}}
                            <span>Subtotal ({{ collect($cartItems)->sum('quantity') }} item)</span>
                            {{-- Tampilkan total harga dari Controller --}}
                            <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Admin</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        {{-- Link ke route checkout --}}
                        <a href="{{ route('checkout.index') }}" id="checkout-btn" class="btn btn-primary">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            @else
                {{-- Tampilkan pesan jika keranjang kosong --}}
                <div class="empty-cart-message" id="empty-cart-message">
                     <p>Keranjang Anda masih kosong.</p>
                     <a href="{{ route('home') }}" class="btn btn-primary">Mulai Belanja</a>
                </div>
            @endif

        </div>
    </div>

    {{-- Slot JavaScript Tambahan (INI YANG BARU) --}}
    <x-slot name="scripts">
        <script>
            // === VARIABEL GLOBAL & ELEMENT ===
            const cartItemsList = document.getElementById('cart-items-list');
            const cartSummary = document.getElementById('cart-summary');
            const emptyCartMessage = document.getElementById('empty-cart-message');
            const cartLayout = document.getElementById('cart-layout');
            // Ambil CSRF token dari meta tag (perlu ditambah di app.blade.php)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

             // Fungsi untuk update counter header (ambil dari layout jika ada)
             function updateCartCounter(totalItems) {
                const cartCountEl = document.getElementById('cart-count-header');
                if(cartCountEl) cartCountEl.textContent = totalItems;
             }

             // Fungsi untuk format Rupiah
             function formatRupiah(number) {
                 // Periksa apakah number valid sebelum format
                 const num = parseFloat(number);
                 if (isNaN(num)) return 'Rp -'; // Tampilkan '-' jika tidak valid
                 return 'Rp ' + num.toLocaleString('id-ID');
             }

            // --- FUNGSI RENDER RINGKASAN (Dipanggil setelah update/hapus) ---
            function renderSummary(totalItems, totalPrice) {
                 // Update counter di header
                 updateCartCounter(totalItems);

                if (totalItems > 0 && cartSummary) { // Pastikan cartSummary ada
                    const summaryHtml = `
                        <h2>Ringkasan Pesanan</h2>
                        <div class="summary-row">
                            <span>Subtotal (${totalItems} item)</span>
                            <span>${formatRupiah(totalPrice)}</span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Admin</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>${formatRupiah(totalPrice)}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" id="checkout-btn" class="btn btn-primary">
                            Lanjut ke Checkout
                        </a>
                    `;
                    cartSummary.innerHTML = summaryHtml;
                    if(cartLayout) cartLayout.style.display = 'grid'; // Pastikan layout tampil
                    if(emptyCartMessage) emptyCartMessage.style.display = 'none'; // Sembunyikan pesan kosong
                } else {
                    // Jika keranjang jadi kosong setelah update/hapus
                    if(cartSummary) cartSummary.innerHTML = ''; // Kosongkan summary jika ada
                    if(cartLayout) cartLayout.style.display = 'none'; // Sembunyikan layout item & summary
                    if(emptyCartMessage) { // Pastikan emptyCartMessage ada
                        emptyCartMessage.style.display = 'block'; // Tampilkan pesan kosong
                        emptyCartMessage.innerHTML = `
                            <p>Keranjang Anda masih kosong.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Mulai Belanja</a>
                        `;
                    }
                }
            }


            // --- FUNGSI TANGANI PERUBAHAN JUMLAH (AJAX PATCH) ---
            function handleQuantityChange(e) {
                const cartId = e.target.getAttribute('data-id');
                const newQuantity = parseInt(e.target.value);
                const inputElement = e.target; // Simpan elemen input

                 // Validasi minimal 1
                if (newQuantity < 1 || isNaN(newQuantity)) { // Cek juga jika NaN
                    inputElement.value = 1; // Kembalikan ke 1 jika < 1 atau tidak valid
                    // Jalankan update lagi dengan nilai 1
                    handleQuantityChange({ target: inputElement }); // Rekursif call (hati-hati) atau panggil fetch langsung
                    return; // Hentikan proses
                }

                // Kirim request PATCH ke backend
                // Pastikan route() menghasilkan URL yang benar
                fetch(`{{ url('/keranjang/update') }}/${cartId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken // Kirim CSRF Token dari meta tag
                    },
                    body: JSON.stringify({ quantity: newQuantity })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Gagal update jumlah.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // SUKSES! Update summary
                    console.log(data.message); // Tampilkan pesan sukses di console
                    renderSummary(data.totalItems, data.totalPrice);
                    // (Optional) Update subtotal per item jika perlu ditampilkan
                })
                .catch(error => {
                    console.error('Error updating quantity:', error);
                    alert('Gagal mengupdate jumlah: ' + error.message);
                    // Kembalikan nilai input ke nilai sebelumnya? (Lebih kompleks, perlu simpan nilai lama)
                    // Untuk sementara, biarkan saja
                });
            }

            // --- FUNGSI TANGANI HAPUS ITEM (AJAX DELETE) ---
            function handleRemoveItem(e) {
                const cartId = e.target.getAttribute('data-id');
                const cardElement = e.target.closest('.cart-item-card'); // Cari elemen card terdekat

                if (!confirm('Anda yakin ingin menghapus item ini?')) {
                    return; // Batalkan jika user klik Cancel
                }

                // Kirim request DELETE ke backend
                 // Pastikan route() menghasilkan URL yang benar
                fetch(`{{ url('/keranjang/hapus') }}/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken // Kirim CSRF Token dari meta tag
                    }
                })
                .then(response => {
                     if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Gagal menghapus item.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // SUKSES! Hapus card item dari HTML & update summary
                    console.log(data.message);
                    if (cardElement) {
                        cardElement.remove(); // Hapus elemen card dari DOM
                    }
                    renderSummary(data.totalItems, data.totalPrice);
                })
                .catch(error => {
                     console.error('Error removing item:', error);
                     alert('Gagal menghapus item: ' + error.message);
                });
            }

            // --- FUNGSI TAMBAHKAN EVENT LISTENERS ---
            function addListeners() {
                const removeButtons = document.querySelectorAll('.remove-item-btn');
                removeButtons.forEach(button => {
                    // Hapus listener lama jika ada (mencegah duplikat)
                    button.removeEventListener('click', handleRemoveItem);
                    // Tambah listener baru
                    button.addEventListener('click', handleRemoveItem);
                });

                const quantityInputs = document.querySelectorAll('.quantity-input');
                quantityInputs.forEach(input => {
                     // Hapus listener lama jika ada
                    input.removeEventListener('change', handleQuantityChange);
                    // Tambah listener baru
                    input.addEventListener('change', handleQuantityChange);
                });
            }

            // --- JALANKAN SAAT HALAMAN DIBUKA ---
            document.addEventListener('DOMContentLoaded', function() {
                 console.log("Halaman Keranjang Backend Loaded");

                 // Panggil renderSummary sekali saat load untuk menampilkan total awal
                 // Ambil totalItems dan totalPrice dari PHP (jika keranjang tidak kosong)
                 @if(isset($cartItems) && count($cartItems) > 0)
                    renderSummary({{ collect($cartItems)->sum('quantity') }}, {{ $totalPrice }});
                 @else
                    // Jika kosong, renderSummary akan menampilkan pesan kosong
                    renderSummary(0, 0);
                 @endif

                 // Update counter header saat load (ambil dari PHP)
                 updateCartCounter({{ isset($cartItems) ? collect($cartItems)->sum('quantity') : 0 }});

                 // Tambahkan listener ke item yang sudah ada saat load
                 addListeners();
            });

        </script>
    </x-slot>

</x-app-layout>
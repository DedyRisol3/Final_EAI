<x-app-layout>
    <x-slot name="title">
        PenjahitKu - Jasa Pembuatan Baju Custom
    </x-slot>

    {{-- Search bar di header halaman ini --}}
    <div class="header-nav-extra" style="background-color: var(--white-color); padding-bottom: 1rem; border-bottom: 1px solid var(--grey-color);">
        <div class="container flex justify-end">
             <div class="search-bar w-full max-w-md">
                <input type="text" id="search-input" placeholder="Cari jasa jahit..." class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>

    {{-- Konten Utama Halaman Welcome --}}
    <section id="kategori" class="section">
        <div class="container">
            <h2 class="text-2xl font-semibold mb-6">Kategori Jasa</h2>
            <div class="category-grid">
                <a class="category-card active" data-filter="all"><div class="category-icon"><i class="fas fa-store"></i></div><h4>Semua</h4></a>
                <a class="category-card" data-filter="kemeja"><div class="category-icon"><i class="fas fa-user-tie"></i></div><h4>Kemeja</h4></a>
                <a class="category-card" data-filter="gaun"><div class="category-icon"><i class="fas fa-female"></i></div><h4>Gaun & Kebaya</h4></a>
                <a class="category-card" data-filter="kaos"><div class="category-icon"><i class="fas fa-tshirt"></i></div><h4>Kaos & Sablon</h4></a>
                <a class="category-card" data-filter="seragam"><div class="category-icon"><i class="fas fa-users"></i></div><h4>Seragam</h4></a>
                <a class="category-card" data-filter="batik"><div class="category-icon"><i class="fas fa-palette"></i></div><h4>Batik</h4></a>
                <a class="category-card" data-filter="permak"><div class="category-icon"><i class="fas fa-cut"></i></div><h4>Permak</h4></a>
            </div>
        </div>
    </section>

    <section id="produk" class="section">
        <div class="container">
            <h2 class="text-2xl font-semibold mb-6" id="produk-title">Semua Jasa</h2>
            <div class="product-grid" id="product-grid">
                @forelse ($products as $product)
                    <div class="product-card"
                         data-category="{{ $product->category }}"
                         data-name="{{ strtolower($product->name) }}">

                        {{-- Wrapper Div untuk Gambar --}}
                        <div class="image-container">
                             <img src="{{ $product->image_url ?: 'https://via.placeholder.com/400x300.png?text=No+Image' }}"
                                  alt="{{ $product->name }}">
                        </div>
                        {{-- Akhir Wrapper Div --}}

                        <div class="product-content">
                            <span class="product-tag">{{ ucfirst($product->category) }}</span>
                            <h3>{{ $product->name }}</h3>
                            {{-- Gunakan Str::limit untuk membatasi deskripsi --}}
                            <p>{{ Str::limit($product->description ?: 'Deskripsi belum tersedia.', 100) }}</p>
                            <a href="{{ url('produk/' . $product->slug) }}" class="btn">Lihat Detail</a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                         <p>Belum ada produk yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
            <div id="no-results" style="display: none;">
                <p>Maaf, produk yang Anda cari tidak ditemukan.</p>
            </div>
        </div>
    </section>

    {{-- Slot untuk JavaScript tambahan --}}
    <x-slot name="scripts">
        <script>
            // === Kode JavaScript Filter (tetap sama) ===
            document.addEventListener('DOMContentLoaded', function() {
                const filterButtons = document.querySelectorAll('.category-card');
                const productCards = document.querySelectorAll('.product-card');
                const productTitle = document.getElementById('produk-title');
                const searchInput = document.getElementById('search-input');
                const noResultsMsg = document.getElementById('no-results');

                let currentCategory = 'all';
                let currentSearchTerm = '';

                function filterProducts() {
                    let itemsFound = 0;
                    productCards.forEach(card => {
                        const cardCategory = card.getAttribute('data-category');
                        const productName = card.getAttribute('data-name');
                        const categoryMatch = (currentCategory === 'all' || cardCategory === currentCategory);
                        const searchMatch = productName ? productName.includes(currentSearchTerm) : false;

                        if (categoryMatch && searchMatch) {
                            card.classList.remove('hidden');
                            itemsFound++;
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    noResultsMsg.style.display = (itemsFound === 0 && productCards.length > 0) ? 'block' : 'none';
                 }

                function handleCategoryClick(e) {
                    e.preventDefault();
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    currentCategory = this.getAttribute('data-filter');
                    const filterName = this.querySelector('h4').textContent;
                    productTitle.textContent = currentCategory === 'all' ? 'Semua Jasa' : `Kategori: ${filterName}`;
                    filterProducts();
                 }

                function handleSearchInput(e) {
                    currentSearchTerm = e.target.value.toLowerCase();
                    filterProducts();
                 }

                filterButtons.forEach(button => button.addEventListener('click', handleCategoryClick));
                if(searchInput) {
                    searchInput.addEventListener('keyup', handleSearchInput);
                }
                if(productCards.length > 0) {
                     filterProducts();
                }
            });
        </script>
    </x-slot>

</x-app-layout>
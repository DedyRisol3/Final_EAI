<x-app-layout>
    <x-slot name="title">
        Kelola Produk - Admin Panel
    </x-slot>

    <x-slot name="styles">
        {{-- Style tambahan untuk tabel admin --}}
        <style>
            .admin-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
            .admin-table th, .admin-table td { border: 1px solid var(--grey-color); padding: 0.75rem 1rem; text-align: left; vertical-align: top; }
            .admin-table th { background-color: var(--light-color); font-weight: 600; }
            .admin-table td img { max-width: 80px; height: auto; border-radius: 4px; }
            .admin-table .actions a, .admin-table .actions button { display: inline-block; margin-right: 0.5rem; margin-bottom: 0.5rem; padding: 0.3rem 0.6rem; font-size: 0.85rem; border-radius: 4px; text-decoration: none; }
            .admin-table .actions .edit-btn { background-color: #f59e0b; color: white; border: none; }
            .admin-table .actions .edit-btn:hover { background-color: #d97706; }
            .admin-table .actions .delete-btn { background-color: #ef4444; color: white; border: none; cursor: pointer; }
            .admin-table .actions .delete-btn:hover { background-color: #dc2626; }
            .add-product-btn { display: inline-block; padding: 0.6rem 1.2rem; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 5px; font-weight: 600; transition: background-color 0.3s ease; }
            .add-product-btn:hover { background-color: #006a6a; }
            .pagination { margin-top: 1.5rem; }
            .alert-error { background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c; }
            /* Style tombol kembali */
            .back-button {
                display: inline-block;
                padding: 0.5rem 1rem;
                background-color: #6c757d; /* Abu-abu */
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 500;
                font-size: 0.9rem;
                transition: background-color 0.3s ease;
                margin-bottom: 1.5rem; /* Jarak ke bawah */
            }
            .back-button:hover { background-color: #5a6268; }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="container">
            {{-- === TOMBOL KEMBALI === --}}
            <a href="{{ route('admin.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
            {{-- === AKHIR TOMBOL KEMBALI === --}}

            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                 <h1 class="text-3xl font-semibold">Kelola Produk</h1>
                 <a href="{{ route('admin.produk.create') }}" class="add-product-btn">
                     <i class="fas fa-plus mr-2"></i>Tambah Produk Baru
                 </a>
            </div>

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                <div class="mb-4 alert-error px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><img src="{{ $product->image_url ?: 'https://via.placeholder.com/80x80.png?text=N/A' }}" alt="{{ $product->name }}"></td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ ucfirst($product->category) }}</td>
                                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td class="actions">
                                                <a href="{{ route('admin.produk.edit', ['productId' => $product->id]) }}" class="edit-btn">Edit</a>
                                                <form action="{{ route('admin.produk.destroy', ['productId' => $product->id]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 pagination">
                            {{ $products->links() }}
                        </div>
                    @else
                        <p>Belum ada produk.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
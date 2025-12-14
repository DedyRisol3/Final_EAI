<x-app-layout>
    <x-slot name="title">
        Admin Dashboard - PenjahitKu
    </x-slot>

    <x-slot name="styles">
        {{-- Style untuk kartu ringkasan --}}
        <style>
            .summary-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }
            .summary-card {
                background-color: var(--white-color);
                padding: 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                border: 1px solid var(--grey-color);
                text-align: center;
            }
            .summary-card .count {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-color);
                display: block;
                margin-bottom: 0.5rem;
            }
            .summary-card .label {
                font-size: 1rem;
                color: #555;
                font-weight: 500;
            }
             /* Style untuk tombol link (mirip add-product-btn) */
            .admin-action-link {
                 display: inline-block;
                 padding: 0.75rem 1.5rem; /* Padding lebih besar */
                 background-color: var(--primary-color);
                 color: white;
                 text-decoration: none;
                 border-radius: 5px;
                 font-weight: 600;
                 transition: background-color 0.3s ease;
                 margin-right: 1rem; /* Jarak antar tombol */
                 margin-top: 0.5rem; /* Jarak dari teks */
            }
             .admin-action-link:hover {
                 background-color: #006a6a;
             }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <h1 class="text-3xl font-semibold mb-6">Admin Dashboard</h1>

            {{-- Tampilkan Pesan Sukses/Error jika ada --}}
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

            {{-- Bagian Ringkasan Data --}}
            @if(isset($summaryData))
                <div class="summary-grid">
                    <div class="summary-card">
                        <span class="count">{{ $summaryData['totalProducts'] ?? 0 }}</span>
                        <span class="label">Total Produk</span>
                    </div>
                    <div class="summary-card">
                        <span class="count">{{ $summaryData['pendingOrders'] ?? 0 }}</span>
                        <span class="label">Pesanan Pending</span>
                    </div>
                    <div class="summary-card">
                        <span class="count">{{ $summaryData['paidOrders'] ?? 0 }}</span>
                        <span class="label">Pesanan Dibayar</span>
                    </div>
                    <div class="summary-card">
                        <span class="count">{{ $summaryData['shippedOrders'] ?? 0 }}</span>
                        <span class="label">Pesanan Dikirim</span>
                    </div>
                </div>
            @endif

            {{-- === PERBAIKAN TAMPILAN WELCOME & LINKS === --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Tambahkan padding lebih besar (p-8), tengahkan teks --}}
                <div class="p-8 text-gray-900 text-center">
                    {{-- Ubah style paragraf --}}
                    <p class="text-xl font-medium mb-2">Selamat datang di Panel Admin!</p>
                    <p class="text-gray-600 mb-6">Di sini Anda bisa mengelola Produk dan Pesanan dengan mudah.</p>

                    {{-- Ubah style link menjadi tombol --}}
                    <div class="mt-4">
                        <a href="{{ route('admin.produk.index') }}" class="admin-action-link">
                            <i class="fas fa-box-open mr-2"></i> Kelola Produk
                        </a>
                        <a href="{{ route('admin.pesanan.index') }}" class="admin-action-link">
                             <i class="fas fa-receipt mr-2"></i> Kelola Pesanan
                        </a>
                    </div>
                </div>
            </div>
            {{-- === AKHIR PERBAIKAN === --}}
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="title">
        Kelola Pesanan - Admin Panel
    </x-slot>

    <x-slot name="styles">
        <style>
            .admin-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
            .admin-table th, .admin-table td { border: 1px solid var(--grey-color); padding: 0.75rem 1rem; text-align: left; vertical-align: top; }
            .admin-table th { background-color: var(--light-color); font-weight: 600; }
             .status-pending { background-color: #fef9c3; color: #ca8a04; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             .status-paid { background-color: #dcfce7; color: #16a34a; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             /* Tambah status lain jika perlu */
             .detail-btn { background-color: #3b82f6; color: white; border:none; display: inline-block; margin-right: 0.5rem; margin-bottom: 0.5rem; padding: 0.3rem 0.6rem; font-size: 0.85rem; border-radius: 4px; text-decoration: none; }
             .detail-btn:hover{ background-color: #2563eb;}
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

            <h1 class="text-3xl font-semibold mb-6">Kelola Pesanan</h1>

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
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Nama Pemesan</th>
                                        <th>Email</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Tanggal Pesan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->name ?? 'N/A' }}</td>
                                            <td>{{ $order->email ?? 'N/A' }}</td>
                                            <td>Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="status-{{ strtolower($order->status ?? 'unknown') }}">
                                                    {{ ucfirst($order->status ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'N/A' }}</td>
                                            <td class="actions">
                                                <a href="{{ route('admin.pesanan.show', ['orderId' => $order->id]) }}" class="detail-btn">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 pagination">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <p>Belum ada pesanan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
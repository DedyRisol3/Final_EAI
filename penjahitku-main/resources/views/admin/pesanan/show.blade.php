<x-app-layout>
    <x-slot name="title">
        Detail Pesanan #{{ $order->id }} - Admin Panel
    </x-slot>

    <x-slot name="styles">
        <style>
            .detail-section { margin-bottom: 2rem; }
            .detail-section h2 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; color: var(--primary-color);}
            .detail-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; }
            .detail-grid div { margin-bottom: 0.5rem; }
            .detail-grid strong { display: block; color: #555; font-size: 0.9rem; margin-bottom: 0.2rem;}
            .item-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
            .item-table th, .item-table td { border: 1px solid var(--grey-color); padding: 0.75rem 1rem; text-align: left; vertical-align: top; }
            .item-table th { background-color: var(--light-color); font-weight: 600; }
            .item-table td img { max-width: 60px; height: auto; border-radius: 4px; }
            .item-table .item-custom-details { font-size: 0.85rem; color: #444; background: #f9f9f9; padding: 0.5rem; border-radius: 4px; margin-top: 0.5rem;}
             .status-pending { background-color: #fef9c3; color: #ca8a04; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             .status-paid { background-color: #dcfce7; color: #16a34a; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             .status-shipped { background-color: #dbeafe; color: #2563eb; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             .status-completed { background-color: #e0e7ff; color: #4338ca; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             .status-cancelled { background-color: #fee2e2; color: #dc2626; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
             .status-unknown { background-color: #f3f4f6; color: #6b7280; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.8rem; font-weight: 500;}
            .back-link { display: inline-block; margin-top: 2rem; color: var(--primary-color); text-decoration: none; font-weight: 500;}
            .back-link:hover { text-decoration: underline; }
            /* Style form update status */
            .update-status-form { margin-top: 1rem; display: flex; align-items: center; gap: 1rem; }
            .update-status-form select { flex-grow: 1; max-width: 200px;} /* Batasi lebar dropdown */
        </style>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <h1 class="text-3xl font-semibold mb-6">Detail Pesanan #{{ $order->id }}</h1>

             {{-- Tampilkan Pesan Sukses jika status diupdate --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Bagian Informasi Pemesan & Pengiriman --}}
                    <div class="detail-section">
                        <h2>Informasi Pemesan & Pengiriman</h2>
                        <div class="detail-grid">
                            <div><strong>Nama Pemesan:</strong> {{ $order->name ?? 'N/A' }}</div>
                            <div><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</div>
                            <div><strong>Nomor Telepon:</strong> {{ $order->phone ?? 'N/A' }}</div>
                            <div><strong>Tanggal Pesan:</strong> {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'N/A' }}</div>
                            <div>
                                <strong>Status Pesanan Saat Ini:</strong><br>
                                <span class="status-{{ strtolower($order->status ?? 'unknown') }}">
                                    {{ ucfirst($order->status ?? 'Unknown') }}
                                </span>

                                {{-- === FORM UPDATE STATUS === --}}
                                <form action="{{ route('admin.pesanan.update', ['orderId' => $order->id]) }}" method="POST" class="update-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <label for="status" class="sr-only">Ubah Status:</label> {{-- Label tersembunyi --}}
                                    <select name="status" id="status" class="border-gray-300 rounded-md shadow-sm">
                                        <option value="pending" @selected($order->status == 'pending')>Pending</option>
                                        <option value="paid" @selected($order->status == 'paid')>Paid</option>
                                        <option value="shipped" @selected($order->status == 'shipped')>Shipped</option>
                                        <option value="completed" @selected($order->status == 'completed')>Completed</option>
                                        <option value="cancelled" @selected($order->status == 'cancelled')>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary px-4 py-2 text-sm">Update Status</button> {{-- Ukuran tombol lebih kecil --}}
                                </form>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                {{-- === AKHIR FORM === --}}
                            </div>
                            <div><strong>Kurir:</strong> {{ strtoupper($order->courier ?? 'N/A') }}</div>
                            <div><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</div>
                        </div>
                    </div>

                    {{-- Bagian Item Pesanan (Tetap Sama) --}}
                    <div class="detail-section">
                        <h2>Item Pesanan</h2>
                        @if ($order->items && $order->items->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="item-table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan</th>
                                            <th>Subtotal</th>
                                            <th>Detail Kustom</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="flex items-center gap-4">
                                                        <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/60x60.png?text=N/A' }}" alt="{{ $item->product_name ?? 'Produk' }}">
                                                        <span>{{ $item->product_name ?? 'Nama Produk Tidak Diketahui' }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $item->quantity ?? 0 }}</td>
                                                <td>Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 0, ',', '.') }}</td>
                                                <td>
                                                    <div class="item-custom-details">
                                                        Ukuran: {{ $item->custom_size ?: '-' }} <br>
                                                        LD: {{ $item->custom_chest ?: '-' }} cm <br>
                                                        Catatan: {{ $item->custom_notes ?: '-' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>Tidak ada item dalam pesanan ini.</p>
                        @endif
                    </div>

                     {{-- Bagian Total Keseluruhan (Tetap Sama) --}}
                    <div class="detail-section">
                        <h2>Total Pembayaran</h2>
                         <p class="text-xl font-semibold">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
                    </div>

                    {{-- Tombol Kembali (Tetap Sama) --}}
                    <a href="{{ route('admin.pesanan.index') }}" class="back-link">
                       <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pesanan
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
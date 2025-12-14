<x-app-layout>
    <x-slot name="title">
        Edit Produk: {{ $product->name }} - Admin Panel
    </x-slot>

    <x-slot name="styles">
        {{-- Style sama seperti create --}}
        <style>
            .form-group { margin-bottom: 1.5rem; }
            .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
            .btn-primary { background-color: var(--primary-color); color: var(--white-color); }
            .btn-primary:hover { background-color: #006a6a; }
            .btn-secondary { background-color: #6c757d; color: white; }
            .btn-secondary:hover { background-color: #5a6268; }
            .text-red-500 { color: #ef4444; }
            .text-sm { font-size: 0.875rem; }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <h1 class="text-3xl font-semibold mb-6">Edit Produk: {{ $product->name }}</h1>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- === PERBAIKI PEMANGGILAN ROUTE DI SINI === --}}
                    {{-- Pastikan nama parameter adalah 'productId' --}}
                    <form action="{{ route('admin.produk.update', ['productId' => $product->id]) }}" method="POST">
                    {{-- === AKHIR PERBAIKAN === --}}
                        @csrf
                        @method('PATCH')

                        {{-- Nama Produk --}}
                        <div class="form-group">
                            <label for="name">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name"
                                   class="border-gray-300 rounded-md shadow-sm w-full"
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="form-group">
                            <label for="category">Kategori <span class="text-red-500">*</span></label>
                            <select id="category" name="category" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kemeja" @selected(old('category', $product->category) == 'kemeja')>Kemeja</option>
                                <option value="gaun" @selected(old('category', $product->category) == 'gaun')>Gaun & Kebaya</option>
                                <option value="kaos" @selected(old('category', $product->category) == 'kaos')>Kaos & Sablon</option>
                                <option value="seragam" @selected(old('category', $product->category) == 'seragam')>Seragam</option>
                                <option value="batik" @selected(old('category', $product->category) == 'batik')>Batik</option>
                                <option value="permak" @selected(old('category', $product->category) == 'permak')>Permak</option>
                            </select>
                            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Harga --}}
                        <div class="form-group">
                            <label for="price">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" step="1000" min="0"
                                   class="border-gray-300 rounded-md shadow-sm w-full"
                                   value="{{ old('price', $product->price) }}" required>
                            @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" rows="4"
                                      class="border-gray-300 rounded-md shadow-sm w-full">{{ old('description', $product->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                         {{-- URL Gambar --}}
                        <div class="form-group">
                            <label for="image_url">URL Gambar</label>
                            <input type="url" id="image_url" name="image_url"
                                   class="border-gray-300 rounded-md shadow-sm w-full"
                                   value="{{ old('image_url', $product->image_url) }}" placeholder="Contoh: https://...">
                            @error('image_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-6 flex items-center gap-4">
                            <button type="submit" class="btn btn-primary">Update Produk</button>
                            <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
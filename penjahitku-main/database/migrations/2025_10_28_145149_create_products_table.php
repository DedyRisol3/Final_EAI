<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (angka unik)
            $table->string('name'); // Nama produk (teks singkat)
            $table->string('slug')->unique(); // ID unik berupa teks (misal: 'kemeja-formal') untuk URL
            $table->text('description')->nullable(); // Deskripsi panjang, boleh kosong
            $table->decimal('price', 15, 2); // Harga (angka desimal, total 15 digit, 2 di belakang koma)
            $table->string('image_url')->nullable(); // Link gambar, boleh kosong
            $table->string('category'); // Kategori (teks singkat, misal: 'kemeja', 'gaun')
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }
};

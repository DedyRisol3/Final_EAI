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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel 'orders'. Jika order dihapus, itemnya ikut terhapus.
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // Menghubungkan ke tabel 'products'. Jika produk dihapus, di sini jadi null.
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name'); // Nama produk saat dipesan
            $table->integer('quantity'); // Jumlah item ini
            $table->decimal('price', 15, 2); // Harga per item saat dipesan
            $table->string('custom_size')->nullable(); // Ukuran custom (S/M/L)
            $table->string('custom_chest')->nullable(); // Lingkar dada custom
            $table->text('custom_notes')->nullable(); // Catatan custom
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

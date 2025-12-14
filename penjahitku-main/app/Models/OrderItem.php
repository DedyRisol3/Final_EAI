<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Untuk relasi

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'custom_size',
        'custom_chest',
        'custom_notes',
    ];

    // Definisikan relasi: Satu OrderItem milik satu Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Definisikan relasi: Satu OrderItem milik satu Product (bisa null)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
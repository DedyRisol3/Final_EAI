<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'courier',
        'payment_method',
        'discount_value',
        'total_price',
        'status',
    ];

    /**
     * Define the relationship: An Order has many OrderItems.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        // === PERUBAHAN DI SINI ===
        // Secara eksplisit definisikan foreign key ('order_id' di tabel order_items)
        // dan local key ('id' di tabel orders)
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
        // === AKHIR PERUBAHAN ===
    }

    // (Relasi ke User jika perlu)
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}

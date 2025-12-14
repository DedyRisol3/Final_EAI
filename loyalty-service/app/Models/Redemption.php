<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    protected $fillable = [
        'user_id',
        'points_used',
        'discount_value',
        'order_id'
    ];
}

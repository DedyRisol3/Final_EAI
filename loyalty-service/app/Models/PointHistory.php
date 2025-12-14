<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'points',
        'order_id',
        'redeem_type',
        'description',
    ];
}

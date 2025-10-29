<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class KainKeluar extends Model
{
    use HasFactory;

    protected $table = 'orders'; // karena datanya di tabel orders

    protected $fillable = [
        'order_number', 'customer_name', 'customer_phone',
        'status', 'delivery_type', 'address', 'pickup_date',
        'pickup_time', 'weight', 'total_price', 'notes',
        'order_date', 'user_id'
    ];
}


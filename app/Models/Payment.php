<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_number',      // snapshot dari orders
        'total_price',       // snapshot dari orders
        'customer_name',     // snapshot dari orders
        'payment_method',
        'payment_status',
        'amount',
        'paid_at',
        'token',             // opsional: token dari Midtrans
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi ke model Order - YANG BENAR
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
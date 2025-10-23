<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'status',
        'delivery_type',
        'address',
        'pickup_time',
        'pickup_date',
        'weight',
        'total_price',
        'notes',
        'order_date',
        'user_id'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'datetime',
        'pickup_date' => 'date',
        'pickup_time' => 'datetime'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}

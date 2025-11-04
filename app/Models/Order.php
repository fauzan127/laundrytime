<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'status',           // status order (bukan payment)
        'delivery_type',
        'address',
        'pickup_time',
        'pickup_date',
        'weight',
        'total_price',
        'notes',
        'order_date',
        'user_id',
        // HAPUS: 'payment_status', -> pindah ke tabel payments
        'transaction_date',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'datetime',
        'pickup_date' => 'date',
        'transaction_date' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    // Accessor untuk mendapatkan payment_status dari relasi payment
    public function getPaymentStatusAttribute()
    {
        return $this->payment ? $this->payment->payment_status : 'Belum Dibayar';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(
                Order::whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );
        });
    }
}
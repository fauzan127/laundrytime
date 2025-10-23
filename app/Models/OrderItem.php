<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_type_id',
        'clothing_type_id',
        'weight',
        'price'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function clothingType()
    {
        return $this->belongsTo(ClothingType::class);
    }
}

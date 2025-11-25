<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\ServiceType;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'service_type_id',
        'clothing_type_id',
        'weight',
        'price'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'service_type_id' => 'integer',
        'clothing_type_id' => 'integer'
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
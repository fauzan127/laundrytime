<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothingType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'additional_price',
        'is_active'
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
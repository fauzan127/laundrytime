<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_kg',
        'is_active'
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class ServiceType extends Model
{
    protected $fillable = [
        'name',
        'type',           // Tambahkan ini
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

    // Scope untuk filter by type
    public function scopeReguler($query)
    {
        return $query->where('type', 'reguler');
    }

    public function scopeExpress($query)
    {
        return $query->where('type', 'express');
    }
}
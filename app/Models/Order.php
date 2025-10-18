<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'layanan',
        'jenis_pengantaran',
        'alamat',
        'catatan',
        'status',
        'berat',
    ];

    protected $casts = [
        'layanan' => 'array',
        'berat' => 'decimal:2',
    ];
}

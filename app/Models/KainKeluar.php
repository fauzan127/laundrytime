<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KainKeluar extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'layanan',
        'berat',
        'status', // Pastikan ini sesuai dengan kolom di database
        'created_at',
    ];

    public $timestamps = false;
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'kain_keluar'; // nama tabel di database
    protected $primaryKey = 'id'; // sesuaikan kalau bukan 'id'
    protected $fillable = [
        'nama_pelanggan',
        'no_hp_pelanggan',
        'layanan',
        'berat_kg',
        'status_layanan',
        'tanggal_masuk'
    ];
}

<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        $data = Pesanan::all();
        return view('dashboardkainkeluar', compact('data'));
    }

    // opsional: buat untuk detail pesanan
    public function getDetailPesanan($id_pesanan)
    {
        $pesanan = Pesanan::findOrFail($id_pesanan);
        return response()->json($pesanan);
    }
}

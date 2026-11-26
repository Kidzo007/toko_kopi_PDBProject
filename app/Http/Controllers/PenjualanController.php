<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // Menampilkan halaman transaksi penjualan
    public function index()
    {
        // Mengambil margin penjualan yang aktif
        $margin = DB::selectOne("
            SELECT idmargin_penjualan, persen
            FROM margin_penjualan
            WHERE status = 1
            ORDER BY created_at DESC
            LIMIT 1
        ");

        // Jika belum ada margin aktif, gunakan 0%
        $persenMargin = $margin ? $margin->persen : 0;

        // Mengambil barang aktif dan menghitung harga jual
        $barang = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                b.harga AS harga_modal,
                s.nama_satuan,
                fn_harga_jual(b.harga, ?) AS harga_jual
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE b.status = 1
            ORDER BY b.nama ASC
        ", [$persenMargin]);

        return view('penjualan.index', compact(
            'barang',
            'margin',
            'persenMargin'
        ));
    }
}

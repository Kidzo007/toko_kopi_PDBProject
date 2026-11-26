<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KasirDashboardController extends Controller
{
    public function index()
    {
        // TOTAL SELURUH TRANSAKSI
        $totalTransaksi = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM penjualan
        ")->total;


        // TRANSAKSI HARI INI
        $transaksiHariIni = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM penjualan
            WHERE DATE(created_at) = CURDATE()
        ")->total;


        // TOTAL PENJUALAN HARI INI
        $penjualanHariIni = DB::selectOne("
            SELECT COALESCE(SUM(total_nilai), 0) AS total
            FROM penjualan
            WHERE DATE(created_at) = CURDATE()
        ")->total;


        // TOTAL PENDAPATAN KESELURUHAN
        $totalPendapatan = DB::selectOne("
            SELECT COALESCE(SUM(total_nilai), 0) AS total
            FROM penjualan
        ")->total;


        // 10 TRANSAKSI TERBARU
        $transaksiTerbaru = DB::select("
            SELECT
                p.idpenjualan,
                p.created_at,
                p.total_nilai,
                u.username
            FROM penjualan p
            JOIN `user` u ON p.iduser = u.iduser
            ORDER BY p.created_at DESC
            LIMIT 10
        ");


        // DAFTAR BARANG
        $daftarBarang = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                b.harga,
                s.nama_satuan
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE b.status = 1
            ORDER BY b.nama ASC
        ");


        return view('dashboard.kasir', compact(
            'totalTransaksi',
            'transaksiHariIni',
            'penjualanHariIni',
            'totalPendapatan',
            'transaksiTerbaru',
            'daftarBarang'
        ));
    }
}

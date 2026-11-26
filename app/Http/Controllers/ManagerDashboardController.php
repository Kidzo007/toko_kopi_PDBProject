<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // =================================
        // STATISTIK MASTER
        // =================================

        $totalBarang = DB::selectOne("
            SELECT COUNT(*) AS total FROM barang
        ")->total;

        $totalVendor = DB::selectOne("
            SELECT COUNT(*) AS total FROM vendor
        ")->total;

        $totalUser = DB::selectOne("
            SELECT COUNT(*) AS total FROM `user`
        ")->total;


        // =================================
        // STATISTIK TRANSAKSI
        // =================================

        $totalPenjualan = DB::selectOne("
            SELECT COUNT(*) AS total FROM penjualan
        ")->total;

        $totalPengadaan = DB::selectOne("
            SELECT COUNT(*) AS total FROM pengadaan
        ")->total;


        // =================================
        // TOTAL PENDAPATAN PENJUALAN
        // =================================

        $pendapatan = DB::selectOne("
            SELECT COALESCE(SUM(total_nilai), 0) AS total
            FROM penjualan
        ")->total;


        // =================================
        // TOTAL NILAI PENGADAAN
        // =================================

        $nilaiPengadaan = DB::selectOne("
            SELECT COALESCE(SUM(total_nilai), 0) AS total
            FROM pengadaan
        ")->total;


        // =================================
        // 5 TRANSAKSI PENJUALAN TERBARU
        // =================================

        $penjualanTerbaru = DB::select("
            SELECT
                p.idpenjualan,
                p.created_at,
                p.total_nilai,
                u.username
            FROM penjualan p
            JOIN `user` u ON p.iduser = u.iduser
            ORDER BY p.created_at DESC
            LIMIT 5
        ");


        // =================================
        // 5 PENGADAAN TERBARU
        // =================================

        $pengadaanTerbaru = DB::select("
            SELECT
                p.idpengadaan,
                p.timestamp,
                p.total_nilai,
                v.nama_vendor
            FROM pengadaan p
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            ORDER BY p.timestamp DESC
            LIMIT 5
        ");


        return view('dashboard.manager', compact(
            'totalBarang',
            'totalVendor',
            'totalUser',
            'totalPenjualan',
            'totalPengadaan',
            'pendapatan',
            'nilaiPengadaan',
            'penjualanTerbaru',
            'pengadaanTerbaru'
        ));
    }
}

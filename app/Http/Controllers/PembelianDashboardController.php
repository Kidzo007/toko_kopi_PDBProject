<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PembelianDashboardController extends Controller
{
    public function index()
    {
        // TOTAL PENGADAAN
        $totalPengadaan = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM pengadaan
        ")->total;


        // TOTAL NILAI PENGADAAN
        $totalNilaiPengadaan = DB::selectOne("
            SELECT COALESCE(SUM(total_nilai), 0) AS total
            FROM pengadaan
        ")->total;


        // TOTAL VENDOR
        $totalVendor = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM vendor
        ")->total;


        // TOTAL PENGADAAN HARI INI
        $pengadaanHariIni = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM pengadaan
            WHERE DATE(timestamp) = CURDATE()
        ")->total;


        // RIWAYAT PENGADAAN TERBARU
        $pengadaanTerbaru = DB::select("
            SELECT
                p.idpengadaan,
                p.timestamp,
                p.status,
                p.total_nilai,
                v.nama_vendor,
                u.username
            FROM pengadaan p
            JOIN vendor v
                ON p.vendor_idvendor = v.idvendor
            JOIN `user` u
                ON p.user_iduser = u.iduser
            ORDER BY p.timestamp DESC
            LIMIT 10
        ");


        // DETAIL BARANG YANG DIBELI
        $detailPembelian = DB::select("
            SELECT
                dp.iddetail_pengadaan,
                p.idpengadaan,
                p.timestamp,
                b.nama AS nama_barang,
                dp.jumlah,
                dp.harga_satuan,
                dp.sub_total
            FROM detail_pengadaan dp
            JOIN pengadaan p
                ON dp.idpengadaan = p.idpengadaan
            JOIN barang b
                ON dp.idbarang = b.idbarang
            ORDER BY p.timestamp DESC
            LIMIT 10
        ");


        return view('dashboard.pembelian', compact(
            'totalPengadaan',
            'totalNilaiPengadaan',
            'totalVendor',
            'pengadaanHariIni',
            'pengadaanTerbaru',
            'detailPembelian'
        ));
    }
}
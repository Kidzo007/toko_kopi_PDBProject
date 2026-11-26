<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class GudangDashboardController extends Controller
{
    public function index()
    {
        // TOTAL BARANG
        $totalBarang = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM barang
        ")->total;


        // TOTAL BARANG MASUK
        $totalMasuk = DB::selectOne("
            SELECT COALESCE(SUM(masuk), 0) AS total
            FROM kartu_stok
        ")->total;


        // TOTAL BARANG KELUAR
        $totalKeluar = DB::selectOne("
            SELECT COALESCE(SUM(keluar), 0) AS total
            FROM kartu_stok
        ")->total;


        // TOTAL STOK TERKINI
        // Mengambil stok terakhir setiap barang
        $totalStok = DB::selectOne("
            SELECT COALESCE(SUM(ks.stock), 0) AS total
            FROM kartu_stok ks
            WHERE ks.idkartu_stok = (
                SELECT MAX(k2.idkartu_stok)
                FROM kartu_stok k2
                WHERE k2.idbarang = ks.idbarang
            )
        ")->total;


        // DAFTAR STOK TERKINI SETIAP BARANG
        $daftarStok = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                s.nama_satuan,
                COALESCE(ks.stock, 0) AS stok
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN kartu_stok ks
                ON ks.idkartu_stok = (
                    SELECT MAX(k2.idkartu_stok)
                    FROM kartu_stok k2
                    WHERE k2.idbarang = b.idbarang
                )
            ORDER BY b.nama ASC
        ");


        // RIWAYAT STOK TERBARU
        $riwayatStok = DB::select("
            SELECT
                ks.idkartu_stok,
                ks.created_at,
                ks.jenis_transaksi,
                ks.masuk,
                ks.keluar,
                ks.stock,
                b.nama
            FROM kartu_stok ks
            JOIN barang b ON ks.idbarang = b.idbarang
            ORDER BY ks.created_at DESC, ks.idkartu_stok DESC
            LIMIT 10
        ");


        // BARANG DENGAN STOK MENIPIS (STOK <= 5)
        $stokMenipis = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                s.nama_satuan,
                COALESCE(ks.stock, 0) AS stok
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN kartu_stok ks
                ON ks.idkartu_stok = (
                    SELECT MAX(k2.idkartu_stok)
                    FROM kartu_stok k2
                    WHERE k2.idbarang = b.idbarang
                )
            WHERE COALESCE(ks.stock, 0) <= 5
            ORDER BY stok ASC
        ");


        return view('dashboard.gudang', compact(
            'totalBarang',
            'totalMasuk',
            'totalKeluar',
            'totalStok',
            'daftarStok',
            'riwayatStok',
            'stokMenipis'
        ));
    }
}

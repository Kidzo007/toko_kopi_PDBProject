<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    // ==========================================
    // 1. MENAMPILKAN RIWAYAT KARTU STOK
    // ==========================================
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $sql = "
            SELECT
                ks.idkartu_stok,
                ks.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan,
                ks.jenis_transaksi,
                ks.masuk,
                ks.keluar,
                ks.stock,
                ks.created_at,
                ks.idtransaksi
            FROM kartu_stok ks
            JOIN barang b
                ON ks.idbarang = b.idbarang
            JOIN satuan s
                ON b.idsatuan = s.idsatuan
        ";

        $bindings = [];

        if (!empty($keyword)) {
            $sql .= "
                WHERE b.nama LIKE ?
                OR CAST(ks.idtransaksi AS CHAR) LIKE ?
            ";

            $bindings[] = "%{$keyword}%";
            $bindings[] = "%{$keyword}%";
        }

        $sql .= "
            ORDER BY ks.idkartu_stok DESC
        ";

        $kartuStok = DB::select($sql, $bindings);

        // Menghitung jumlah jenis barang
        $jumlahBarang = DB::selectOne("
            SELECT COUNT(DISTINCT idbarang) AS total
            FROM kartu_stok
        ");

        // Menghitung total barang masuk
        $totalMasuk = DB::selectOne("
            SELECT COALESCE(SUM(masuk), 0) AS total
            FROM kartu_stok
        ");

        // Menghitung total barang keluar
        $totalKeluar = DB::selectOne("
            SELECT COALESCE(SUM(keluar), 0) AS total
            FROM kartu_stok
        ");

        return view('kartu_stok.index', compact(
            'kartuStok',
            'jumlahBarang',
            'totalMasuk',
            'totalKeluar',
            'keyword'
        ));
    }


    // ==========================================
    // 2. MENAMPILKAN STOK TERAKHIR SETIAP BARANG
    // ==========================================
    public function stokBarang()
    {
        $stokBarang = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                s.nama_satuan,
                COALESCE((
                    SELECT ks.stock
                    FROM kartu_stok ks
                    WHERE ks.idbarang = b.idbarang
                    ORDER BY ks.idkartu_stok DESC
                    LIMIT 1
                ), 0) AS stok_terakhir
            FROM barang b
            JOIN satuan s
                ON b.idsatuan = s.idsatuan
            WHERE b.status = 1
            ORDER BY b.nama ASC
        ");

        return view('kartu_stok.stok', compact('stokBarang'));
    }
}

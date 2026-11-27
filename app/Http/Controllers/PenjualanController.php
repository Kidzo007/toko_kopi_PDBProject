<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // ==========================================
    // 1. MENAMPILKAN HALAMAN PENJUALAN
    // ==========================================
    public function index()
    {
        // Mengambil margin penjualan aktif
        $margin = DB::selectOne("
            SELECT idmargin_penjualan, persen
            FROM margin_penjualan
            WHERE status = 1
            ORDER BY created_at DESC
            LIMIT 1
        ");

        $persenMargin = $margin ? $margin->persen : 0;

        $idmargin = $margin ? $margin->idmargin_penjualan : null;

        // Mengambil barang aktif beserta harga jual
        $barang = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                b.harga AS harga_modal,
                s.nama_satuan,

                fn_harga_jual(b.harga, ?) AS harga_jual,

                COALESCE((
                    SELECT ks.stock
                    FROM kartu_stok ks
                    WHERE ks.idbarang = b.idbarang
                    ORDER BY ks.idkartu_stok DESC
                    LIMIT 1
                ), 0) AS stok

            FROM barang b

            JOIN satuan s
                ON b.idsatuan = s.idsatuan

            WHERE b.status = 1

            ORDER BY b.nama ASC
        ", [$persenMargin]);

        return view('penjualan.index', compact(
            'barang',
            'margin',
            'persenMargin',
            'idmargin'
        ));
    }


    // ==========================================
    // 2. MENYIMPAN TRANSAKSI PENJUALAN
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'idbarang' => 'required|array|min:1',
            'idbarang.*' => 'required|integer',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        try {

            DB::beginTransaction();

            // Mengambil user yang sedang login
            $iduser = session('iduser');

            if (!$iduser) {
                throw new \Exception(
                    'Session user tidak ditemukan. Silakan login kembali.'
                );
            }

            // Mengambil margin aktif
            $margin = DB::selectOne("
                SELECT idmargin_penjualan, persen
                FROM margin_penjualan
                WHERE status = 1
                ORDER BY created_at DESC
                LIMIT 1
            ");

            if (!$margin) {
                throw new \Exception(
                    'Margin penjualan aktif belum tersedia.'
                );
            }

            // Membuat ID penjualan
            $dataId = DB::selectOne("
                SELECT COALESCE(MAX(idpenjualan), 0) + 1 AS idbaru
                FROM penjualan
            ");

            $idpenjualan = $dataId->idbaru;

            $subtotalTotal = 0;
            $daftarDetail = [];

            // Memeriksa setiap barang
            foreach ($request->idbarang as $index => $idbarang) {

                $jumlah = (int) $request->jumlah[$index];

                if ($jumlah < 1) {
                    throw new \Exception(
                        'Jumlah barang minimal 1.'
                    );
                }

                // Mengambil barang dan mengunci baris barang
                $dataBarang = DB::selectOne("
                    SELECT
                        b.idbarang,
                        b.nama,
                        b.harga,
                        fn_harga_jual(b.harga, ?) AS harga_jual
                    FROM barang b
                    WHERE b.idbarang = ?
                      AND b.status = 1
                    FOR UPDATE
                ", [
                    $margin->persen,
                    $idbarang
                ]);

                if (!$dataBarang) {
                    throw new \Exception(
                        'Barang tidak ditemukan atau tidak aktif.'
                    );
                }

                // Mengunci catatan stok terakhir
                $stok = DB::selectOne("
                    SELECT stock
                    FROM kartu_stok
                    WHERE idbarang = ?
                    ORDER BY idkartu_stok DESC
                    LIMIT 1
                    FOR UPDATE
                ", [$idbarang]);

                $stokTersedia = $stok ? (int) $stok->stock : 0;

                if ($stokTersedia < $jumlah) {
                    throw new \Exception(
                        'Stok ' . $dataBarang->nama .
                        ' tidak mencukupi. Stok tersedia: ' .
                        $stokTersedia
                    );
                }

                $hargaJual = (int) round($dataBarang->harga_jual);

                $subtotal = $hargaJual * $jumlah;

                $subtotalTotal += $subtotal;

                $daftarDetail[] = [
                    'idbarang' => $idbarang,
                    'harga' => $hargaJual,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal
                ];
            }

            // Menghitung PPN menggunakan Function MySQL
            $hasilPPN = DB::selectOne("
                SELECT ROUND(fn_hitung_ppn(?)) AS ppn
            ", [$subtotalTotal]);

            $ppn = (int) $hasilPPN->ppn;

            $total = $subtotalTotal + $ppn;

            // Menyimpan transaksi utama
            DB::insert("
                INSERT INTO penjualan
                (
                    idpenjualan,
                    iduser,
                    idmargin_penjualan,
                    created_at,
                    subtotal_nilai,
                    ppn,
                    total_nilai
                )
                VALUES (?, ?, ?, NOW(), ?, ?, ?)
            ", [
                $idpenjualan,
                $iduser,
                $margin->idmargin_penjualan,
                $subtotalTotal,
                $ppn,
                $total
            ]);

            // Membuat ID detail
            $dataDetailId = DB::selectOne("
                SELECT COALESCE(MAX(iddetail_penjualan), 0) + 1 AS idbaru
                FROM detail_penjualan
            ");

            $idDetail = $dataDetailId->idbaru;

            // Menyimpan detail dan menjalankan trigger
            foreach ($daftarDetail as $detail) {

                DB::insert("
                    INSERT INTO detail_penjualan
                    (
                        iddetail_penjualan,
                        penjualan_idpenjualan,
                        idbarang,
                        harga_satuan,
                        jumlah,
                        subtotal
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ", [
                    $idDetail,
                    $idpenjualan,
                    $detail['idbarang'],
                    $detail['harga'],
                    $detail['jumlah'],
                    $detail['subtotal']
                ]);

                // Trigger otomatis mengurangi stok
                $idDetail++;
            }

            DB::commit();

            return redirect()
                ->route('penjualan.index')
                ->with(
                    'success',
                    'Transaksi berhasil disimpan! Total pembayaran: Rp ' .
                    number_format($total, 0, ',', '.')
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Transaksi gagal: ' . $e->getMessage()
                );
        }
    }
}

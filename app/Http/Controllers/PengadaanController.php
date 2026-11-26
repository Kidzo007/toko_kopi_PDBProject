<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    // ==========================================
    // 1. MENAMPILKAN DAFTAR PENGADAAN
    // ==========================================
    public function index()
    {
        $pengadaan = DB::select("
            SELECT
                p.idpengadaan,
                p.timestamp,
                p.status,
                p.subtotal_nilai,
                p.ppn,
                p.total_nilai,
                v.nama_vendor,
                u.username
            FROM pengadaan p
            JOIN vendor v
                ON p.vendor_idvendor = v.idvendor
            JOIN `user` u
                ON p.user_iduser = u.iduser
            ORDER BY p.timestamp DESC
        ");

        return view('pengadaan.index', compact('pengadaan'));
    }


    // ==========================================
    // 2. MENAMPILKAN FORM TAMBAH PENGADAAN
    // ==========================================
    public function create()
    {
        // Mengambil vendor yang aktif
        $vendor = DB::select("
            SELECT
                idvendor,
                nama_vendor
            FROM vendor
            WHERE status = 'A'
            ORDER BY nama_vendor ASC
        ");

        // Mengambil barang yang aktif
        $barang = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                b.harga,
                s.nama_satuan
            FROM barang b
            JOIN satuan s
                ON b.idsatuan = s.idsatuan
            WHERE b.status = 1
            ORDER BY b.nama ASC
        ");

        return view('pengadaan.create', compact(
            'vendor',
            'barang'
        ));
    }


    // ==========================================
    // 3. MENYIMPAN TRANSAKSI PENGADAAN
    // ==========================================
    public function store(Request $request)
    {
        // VALIDASI DATA FORM
        $request->validate([
            'idvendor' => 'required|integer',
            'idbarang' => 'required|array|min:1',
            'idbarang.*' => 'required|integer',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        try {

            // MEMULAI TRANSAKSI DATABASE
            DB::beginTransaction();


            // ----------------------------------
            // A. CEK VENDOR
            // ----------------------------------

            $vendor = DB::selectOne("
                SELECT idvendor
                FROM vendor
                WHERE idvendor = ?
                AND status = 'A'
            ", [$request->idvendor]);

            if (!$vendor) {
                throw new \Exception(
                    'Vendor tidak ditemukan atau tidak aktif.'
                );
            }


            // ----------------------------------
            // B. MENGAMBIL ID USER LOGIN
            // ----------------------------------

            $iduser = session('iduser');

            if (!$iduser) {
                throw new \Exception(
                    'Session user tidak ditemukan. Silakan login kembali.'
                );
            }


            // ----------------------------------
            // C. MEMBUAT ID PENGADAAN
            // ----------------------------------

            $dataId = DB::selectOne("
                SELECT COALESCE(MAX(idpengadaan), 0) + 1 AS idbaru
                FROM pengadaan
            ");

            $idpengadaan = $dataId->idbaru;


            // ----------------------------------
            // D. MENGHITUNG DETAIL BARANG
            // ----------------------------------

            $subtotalTotal = 0;
            $daftarDetail = [];

            foreach ($request->idbarang as $index => $idbarang) {

                $jumlah = (int) $request->jumlah[$index];

                if ($jumlah < 1) {
                    throw new \Exception(
                        'Jumlah barang minimal 1.'
                    );
                }

                // Mengambil harga langsung dari database
                $dataBarang = DB::selectOne("
                    SELECT
                        idbarang,
                        nama,
                        harga
                    FROM barang
                    WHERE idbarang = ?
                    AND status = 1
                ", [$idbarang]);

                if (!$dataBarang) {
                    throw new \Exception(
                        'Barang dengan ID ' . $idbarang .
                        ' tidak ditemukan atau tidak aktif.'
                    );
                }

                $harga = (int) $dataBarang->harga;

                $subtotal = $harga * $jumlah;

                $subtotalTotal += $subtotal;

                // Menyimpan sementara detail barang
                $daftarDetail[] = [
                    'idbarang' => $idbarang,
                    'harga' => $harga,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal
                ];
            }


            // ----------------------------------
            // E. MENGHITUNG PPN DENGAN FUNCTION
            // ----------------------------------

            $hasilPPN = DB::selectOne("
                SELECT ROUND(fn_hitung_ppn(?)) AS ppn
            ", [$subtotalTotal]);

            $ppn = (int) $hasilPPN->ppn;

            $total = $subtotalTotal + $ppn;


            // ----------------------------------
            // F. MENYIMPAN DATA PENGADAAN
            // ----------------------------------

            DB::insert("
                INSERT INTO pengadaan
                (
                    idpengadaan,
                    user_iduser,
                    vendor_idvendor,
                    `timestamp`,
                    status,
                    subtotal_nilai,
                    ppn,
                    total_nilai
                )
                VALUES (?, ?, ?, NOW(), 'S', ?, ?, ?)
            ", [
                $idpengadaan,
                $iduser,
                $request->idvendor,
                $subtotalTotal,
                $ppn,
                $total
            ]);


            // ----------------------------------
            // G. MEMBUAT ID DETAIL PENGADAAN
            // ----------------------------------

            $dataDetailId = DB::selectOne("
                SELECT COALESCE(MAX(iddetail_pengadaan), 0) + 1 AS idbaru
                FROM detail_pengadaan
            ");

            $idDetail = $dataDetailId->idbaru;


            // ----------------------------------
            // H. MENYIMPAN DETAIL PENGADAAN
            // ----------------------------------

            foreach ($daftarDetail as $detail) {

                DB::insert("
                    INSERT INTO detail_pengadaan
                    (
                        iddetail_pengadaan,
                        idbarang,
                        idpengadaan,
                        harga_satuan,
                        jumlah,
                        sub_total
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ", [
                    $idDetail,
                    $detail['idbarang'],
                    $idpengadaan,
                    $detail['harga'],
                    $detail['jumlah'],
                    $detail['subtotal']
                ]);

                $idDetail++;
            }


            // ----------------------------------
            // I. MENYELESAIKAN TRANSAKSI
            // ----------------------------------

            DB::commit();

            return redirect()
                ->route('pengadaan.index')
                ->with(
                    'success',
                    'Pengadaan berhasil disimpan!'
                );


        } catch (\Throwable $e) {

            // MEMBATALKAN TRANSAKSI JIKA GAGAL
            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Pengadaan gagal disimpan: ' . $e->getMessage()
                );
        }
    }
}

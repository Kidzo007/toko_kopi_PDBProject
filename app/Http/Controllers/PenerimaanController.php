<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    // ==========================================
    // 1. MENAMPILKAN DAFTAR PENERIMAAN
    // ==========================================
    public function index()
    {
        $penerimaan = DB::select("
            SELECT
                p.idpenerimaan,
                p.idpengadaan,
                p.created_at,
                p.status,
                v.nama_vendor,
                u.username
            FROM penerimaan p
            JOIN pengadaan pg
                ON p.idpengadaan = pg.idpengadaan
            JOIN vendor v
                ON pg.vendor_idvendor = v.idvendor
            JOIN `user` u
                ON p.iduser = u.iduser
            ORDER BY p.created_at DESC
        ");

        return view('penerimaan.index', compact('penerimaan'));
    }


    // ==========================================
    // 2. MENAMPILKAN FORM PENERIMAAN
    // ==========================================
    public function create()
    {
        // Mengambil pengadaan yang sudah memiliki detail barang
        $pengadaan = DB::select("
            SELECT
                pg.idpengadaan,
                pg.timestamp,
                v.nama_vendor
            FROM pengadaan pg
            JOIN vendor v
                ON pg.vendor_idvendor = v.idvendor
            WHERE EXISTS (
                SELECT 1
                FROM detail_pengadaan dp
                WHERE dp.idpengadaan = pg.idpengadaan
            )
            ORDER BY pg.timestamp DESC
        ");

        return view('penerimaan.create', compact('pengadaan'));
    }


    // ==========================================
    // 3. MENGAMBIL DETAIL PENGADAAN
    // ==========================================
    public function detailPengadaan($id)
    {
        $detail = DB::select("
            SELECT
                dp.idbarang,
                b.nama,
                s.nama_satuan,
                dp.harga_satuan,
                dp.jumlah,
                COALESCE((
                    SELECT SUM(dpt.jumlah_terima)
                    FROM detail_penerimaan dpt
                    JOIN penerimaan pt
                        ON dpt.idpenerimaan = pt.idpenerimaan
                    WHERE pt.idpengadaan = dp.idpengadaan
                      AND dpt.barang_idbarang = dp.idbarang
                ), 0) AS sudah_diterima
            FROM detail_pengadaan dp
            JOIN barang b
                ON dp.idbarang = b.idbarang
            JOIN satuan s
                ON b.idsatuan = s.idsatuan
            WHERE dp.idpengadaan = ?
            ORDER BY b.nama ASC
        ", [$id]);

        return response()->json($detail);
    }


    // ==========================================
    // 4. MENYIMPAN PENERIMAAN
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|integer',
            'idbarang' => 'required|array|min:1',
            'idbarang.*' => 'required|integer',
            'jumlah_terima' => 'required|array|min:1',
            'jumlah_terima.*' => 'required|integer|min:0',
        ]);

        try {

            DB::beginTransaction();

            // ----------------------------------
            // A. CEK USER LOGIN
            // ----------------------------------

            $iduser = session('iduser');

            if (!$iduser) {
                throw new \Exception(
                    'Session user tidak ditemukan. Silakan login kembali.'
                );
            }


            // ----------------------------------
            // B. CEK PENGADAAN
            // ----------------------------------

            $pengadaan = DB::selectOne("
                SELECT idpengadaan
                FROM pengadaan
                WHERE idpengadaan = ?
                FOR UPDATE
            ", [$request->idpengadaan]);

            if (!$pengadaan) {
                throw new \Exception(
                    'Data pengadaan tidak ditemukan.'
                );
            }


            // ----------------------------------
            // C. VALIDASI BARANG DAN JUMLAH
            // ----------------------------------

            $daftarDetail = [];
            $jumlahTotal = 0;

            foreach ($request->idbarang as $index => $idbarang) {

                $jumlahTerima = (int) $request->jumlah_terima[$index];

                if ($jumlahTerima < 0) {
                    throw new \Exception(
                        'Jumlah penerimaan tidak boleh negatif.'
                    );
                }

                // Mengambil jumlah barang yang dipesan
                $data = DB::selectOne("
                    SELECT
                        dp.idbarang,
                        dp.harga_satuan,
                        dp.jumlah,
                        b.nama,
                        COALESCE((
                            SELECT SUM(dpt.jumlah_terima)
                            FROM detail_penerimaan dpt
                            JOIN penerimaan pt
                                ON dpt.idpenerimaan = pt.idpenerimaan
                            WHERE pt.idpengadaan = dp.idpengadaan
                              AND dpt.barang_idbarang = dp.idbarang
                        ), 0) AS sudah_diterima
                    FROM detail_pengadaan dp
                    JOIN barang b
                        ON dp.idbarang = b.idbarang
                    WHERE dp.idpengadaan = ?
                      AND dp.idbarang = ?
                ", [
                    $request->idpengadaan,
                    $idbarang
                ]);

                if (!$data) {
                    throw new \Exception(
                        'Barang tidak ditemukan dalam pengadaan.'
                    );
                }

                $sisa = (int) $data->jumlah -
                        (int) $data->sudah_diterima;

                if ($jumlahTerima > $sisa) {
                    throw new \Exception(
                        'Jumlah penerimaan ' . $data->nama .
                        ' melebihi sisa pesanan. Sisa: ' . $sisa
                    );
                }

                if ($jumlahTerima > 0) {

                    $subtotal = $jumlahTerima *
                               (int) $data->harga_satuan;

                    $daftarDetail[] = [
                        'idbarang' => $idbarang,
                        'jumlah' => $jumlahTerima,
                        'harga' => $data->harga_satuan,
                        'subtotal' => $subtotal
                    ];

                    $jumlahTotal += $jumlahTerima;
                }
            }

            if (count($daftarDetail) == 0) {
                throw new \Exception(
                    'Masukkan minimal satu jumlah barang yang diterima.'
                );
            }


            // ----------------------------------
            // D. MEMBUAT ID PENERIMAAN
            // ----------------------------------

            $dataId = DB::selectOne("
                SELECT COALESCE(MAX(idpenerimaan), 0) + 1 AS idbaru
                FROM penerimaan
            ");

            $idpenerimaan = $dataId->idbaru;


            // ----------------------------------
            // E. MENYIMPAN DATA PENERIMAAN
            // ----------------------------------

            DB::insert("
                INSERT INTO penerimaan
                (
                    idpenerimaan,
                    idpengadaan,
                    iduser,
                    created_at,
                    status
                )
                VALUES (?, ?, ?, NOW(), 'S')
            ", [
                $idpenerimaan,
                $request->idpengadaan,
                $iduser
            ]);


            // ----------------------------------
            // F. MENYIMPAN DETAIL PENERIMAAN
            // ----------------------------------

            $dataDetailId = DB::selectOne("
                SELECT COALESCE(MAX(iddetail_penerimaan), 0) + 1 AS idbaru
                FROM detail_penerimaan
            ");

            $idDetail = $dataDetailId->idbaru;

            foreach ($daftarDetail as $detail) {

                DB::insert("
                    INSERT INTO detail_penerimaan
                    (
                        iddetail_penerimaan,
                        idpenerimaan,
                        barang_idbarang,
                        jumlah_terima,
                        harga_satuan_terima,
                        sub_total_terima
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ", [
                    $idDetail,
                    $idpenerimaan,
                    $detail['idbarang'],
                    $detail['jumlah'],
                    $detail['harga'],
                    $detail['subtotal']
                ]);

                // Trigger akan berjalan otomatis di sini.
                $idDetail++;
            }


            // ----------------------------------
            // G. COMMIT TRANSAKSI
            // ----------------------------------

            DB::commit();

            return redirect()
                ->route('penerimaan.index')
                ->with(
                    'success',
                    'Penerimaan barang berhasil disimpan!'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Penerimaan gagal: ' . $e->getMessage()
                );
        }
    }
}

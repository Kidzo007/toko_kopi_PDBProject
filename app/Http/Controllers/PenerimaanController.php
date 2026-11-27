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
            // C. GABUNGKAN BARANG YANG SAMA
            // ----------------------------------

            $inputBarang = [];

            foreach ($request->idbarang as $index => $idbarang) {

                $jumlahTerima = (int) $request->jumlah_terima[$index];

                if ($jumlahTerima < 0) {
                    throw new \Exception(
                        'Jumlah penerimaan tidak boleh negatif.'
                    );
                }

                if (!isset($inputBarang[$idbarang])) {
                    $inputBarang[$idbarang] = 0;
                }

                $inputBarang[$idbarang] += $jumlahTerima;
            }


            // ----------------------------------
            // D. VALIDASI BARANG DAN JUMLAH
            // ----------------------------------

            $daftarDetail = [];

            foreach ($inputBarang as $idbarang => $jumlahTerima) {

                // Mengambil data barang dan jumlah pesanan
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

                    FOR UPDATE
                ", [
                    $request->idpengadaan,
                    $idbarang
                ]);

                if (!$data) {
                    throw new \Exception(
                        'Barang tidak ditemukan dalam pengadaan.'
                    );
                }

                // Menghitung sisa pesanan
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
                }
            }

            if (count($daftarDetail) == 0) {
                throw new \Exception(
                    'Masukkan minimal satu jumlah barang yang diterima.'
                );
            }


            // ----------------------------------
            // E. MEMBUAT ID PENERIMAAN
            // ----------------------------------

            $dataId = DB::selectOne("
                SELECT COALESCE(MAX(idpenerimaan), 0) + 1 AS idbaru
                FROM penerimaan
            ");

            $idpenerimaan = $dataId->idbaru;


            // ----------------------------------
            // F. MENYIMPAN DATA PENERIMAAN
            // ----------------------------------

            // Status awal adalah PROSES (P).
            // Status akan diperiksa kembali setelah detail disimpan.

            DB::insert("
                INSERT INTO penerimaan
                (
                    idpenerimaan,
                    idpengadaan,
                    iduser,
                    created_at,
                    status
                )
                VALUES (?, ?, ?, NOW(), 'P')
            ", [
                $idpenerimaan,
                $request->idpengadaan,
                $iduser
            ]);


            // ----------------------------------
            // G. MENYIMPAN DETAIL PENERIMAAN
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

                // Trigger penambahan stok berjalan otomatis.
                $idDetail++;
            }


            // ----------------------------------
            // H. MEMERIKSA STATUS PENERIMAAN
            // ----------------------------------

            // Menghitung apakah masih ada barang
            // yang belum diterima sesuai pesanan.

            $cekSisa = DB::selectOne("
                SELECT COUNT(*) AS jumlah_belum_lengkap

                FROM detail_pengadaan dp

                WHERE dp.idpengadaan = ?

                AND COALESCE((
                    SELECT SUM(dpt.jumlah_terima)

                    FROM detail_penerimaan dpt

                    JOIN penerimaan pt
                        ON dpt.idpenerimaan = pt.idpenerimaan

                    WHERE pt.idpengadaan = dp.idpengadaan
                      AND dpt.barang_idbarang = dp.idbarang

                ), 0) < dp.jumlah

            ", [$request->idpengadaan]);


            // Jika masih ada barang yang belum lengkap,
            // status = PROSES (P).
            //
            // Jika semua barang sudah diterima,
            // status = SELESAI (S).

            if ($cekSisa->jumlah_belum_lengkap > 0) {

                $status = 'P';

            } else {

                $status = 'S';

            }


            // ----------------------------------
            // I. MEMPERBARUI STATUS PENERIMAAN
            // ----------------------------------

            // Semua penerimaan dari pengadaan yang sama
            // mendapatkan status sesuai progres terakhir.

            DB::update("
                UPDATE penerimaan

                SET status = ?

                WHERE idpengadaan = ?

            ", [
                $status,
                $request->idpengadaan
            ]);


            // ----------------------------------
            // J. COMMIT TRANSAKSI
            // ----------------------------------

            DB::commit();

            if ($status == 'P') {

                $pesan = 'Penerimaan berhasil disimpan! Status masih PROSES karena masih ada barang yang belum diterima.';

            } else {

                $pesan = 'Penerimaan berhasil disimpan! Seluruh barang sudah diterima. Status SELESAI.';

            }

            return redirect()
                ->route('penerimaan.index')
                ->with('success', $pesan);


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


    // ==========================================
    // 5. MENAMPILKAN DETAIL PENERIMAAN DARI SP
    // ==========================================
    public function detailSP()
    {
        $data = DB::select("CALL sp_detail_penerimaan()");

        return view('penerimaan.detail-sp', compact('data'));
    }
}

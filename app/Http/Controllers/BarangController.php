<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    // MENAMPILKAN DATA BARANG
    public function index()
    {
        $barang = DB::select("
            SELECT
                b.idbarang,
                b.nama,
                b.jenis,
                b.harga,
                b.status,
                b.idsatuan,
                s.nama_satuan
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            ORDER BY b.idbarang ASC
        ");

        return view('barang.index', compact('barang'));
    }

    // MENAMPILKAN FORM TAMBAH
    public function create()
    {
        $satuan = DB::select("
            SELECT idsatuan, nama_satuan
            FROM satuan
            WHERE status = 1
        ");

        return view('barang.create', compact('satuan'));
    }

    // MENYIMPAN DATA BARANG
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:45',
            'jenis' => 'required|in:J,B',
            'idsatuan' => 'required|integer',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:0,1'
        ]);

        $cekSatuan = DB::selectOne(
            "SELECT idsatuan FROM satuan WHERE idsatuan = ?",
            [$request->idsatuan]
        );

        if (!$cekSatuan) {
            return back()->withInput()
                ->with('error', 'Satuan tidak ditemukan.');
        }

        $id = DB::selectOne("
            SELECT COALESCE(MAX(idbarang), 0) + 1 AS idbaru
            FROM barang
        ");

        DB::insert("
            INSERT INTO barang
            (idbarang, idsatuan, jenis, nama, status, harga)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $id->idbaru,
            $request->idsatuan,
            $request->jenis,
            $request->nama,
            $request->status,
            $request->harga
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil ditambahkan!');
    }

    // MENAMPILKAN FORM EDIT
    public function edit($id)
    {
        $barang = DB::selectOne(
            "SELECT * FROM barang WHERE idbarang = ?",
            [$id]
        );

        if (!$barang) {
            abort(404);
        }

        $satuan = DB::select("
            SELECT idsatuan, nama_satuan
            FROM satuan
        ");

        return view('barang.edit', compact('barang', 'satuan'));
    }

    // MEMPERBARUI DATA BARANG
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:45',
            'jenis' => 'required|in:J,B',
            'idsatuan' => 'required|integer',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE barang
            SET
                idsatuan = ?,
                jenis = ?,
                nama = ?,
                status = ?,
                harga = ?
            WHERE idbarang = ?
        ", [
            $request->idsatuan,
            $request->jenis,
            $request->nama,
            $request->status,
            $request->harga,
            $id
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    // MENGHAPUS DATA BARANG
    public function destroy($id)
    {
        try {
            DB::delete(
                "DELETE FROM barang WHERE idbarang = ?",
                [$id]
            );

            return redirect()->route('barang.index')
                ->with('success', 'Data barang berhasil dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena masih digunakan pada transaksi.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    // MENAMPILKAN DATA SATUAN
    public function index()
    {
        $satuan = DB::select("
            SELECT idsatuan, nama_satuan, status
            FROM satuan
            ORDER BY idsatuan ASC
        ");

        return view('satuan.index', compact('satuan'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('satuan.create');
    }

    // MENYIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|max:45',
            'status' => 'required|in:0,1'
        ]);

        $cek = DB::selectOne(
            "SELECT idsatuan FROM satuan WHERE nama_satuan = ?",
            [$request->nama_satuan]
        );

        if ($cek) {
            return back()->withInput()
                ->with('error', 'Nama satuan sudah tersedia.');
        }

        $id = DB::selectOne("
            SELECT COALESCE(MAX(idsatuan), 0) + 1 AS idbaru
            FROM satuan
        ");

        DB::insert("
            INSERT INTO satuan (idsatuan, nama_satuan, status)
            VALUES (?, ?, ?)
        ", [
            $id->idbaru,
            $request->nama_satuan,
            $request->status
        ]);

        return redirect()->route('satuan.index')
            ->with('success', 'Satuan berhasil ditambahkan!');
    }

    // FORM EDIT
    public function edit($id)
    {
        $satuan = DB::selectOne(
            "SELECT * FROM satuan WHERE idsatuan = ?",
            [$id]
        );

        if (!$satuan) {
            abort(404);
        }

        return view('satuan.edit', compact('satuan'));
    }

    // MEMPERBARUI DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|max:45',
            'status' => 'required|in:0,1'
        ]);

        $cek = DB::selectOne("
            SELECT idsatuan
            FROM satuan
            WHERE nama_satuan = ?
            AND idsatuan != ?
        ", [
            $request->nama_satuan,
            $id
        ]);

        if ($cek) {
            return back()->withInput()
                ->with('error', 'Nama satuan sudah digunakan.');
        }

        DB::update("
            UPDATE satuan
            SET nama_satuan = ?, status = ?
            WHERE idsatuan = ?
        ", [
            $request->nama_satuan,
            $request->status,
            $id
        ]);

        return redirect()->route('satuan.index')
            ->with('success', 'Satuan berhasil diperbarui!');
    }

    // MENGHAPUS DATA
    public function destroy($id)
    {
        try {
            DB::delete(
                "DELETE FROM satuan WHERE idsatuan = ?",
                [$id]
            );

            return redirect()->route('satuan.index')
                ->with('success', 'Satuan berhasil dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()->route('satuan.index')
                ->with('error', 'Satuan tidak bisa dihapus karena masih digunakan oleh barang.');
        }
    }
}

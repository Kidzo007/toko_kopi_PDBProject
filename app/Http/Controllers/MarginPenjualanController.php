<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    // MENAMPILKAN DATA MARGIN
    public function index()
    {
        $margin = DB::select("
            SELECT
                m.idmargin_penjualan,
                m.iduser,
                u.username,
                m.created_at,
                m.persen,
                m.status,
                m.updated_at
            FROM margin_penjualan m
            JOIN user u ON m.iduser = u.iduser
            ORDER BY m.idmargin_penjualan ASC
        ");

        return view('margin.index', compact('margin'));
    }

    // FORM TAMBAH
    public function create()
    {
        $user = DB::select("
            SELECT iduser, username
            FROM user
        ");

        return view('margin.create', compact('user'));
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'iduser' => 'required|integer',
            'persen' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1'
        ]);

        $cekUser = DB::selectOne(
            "SELECT iduser FROM user WHERE iduser = ?",
            [$request->iduser]
        );

        if (!$cekUser) {
            return back()->withInput()
                ->with('error', 'User tidak ditemukan.');
        }

        try {

            DB::transaction(function () use ($request) {

                // Jika margin baru diaktifkan,
                // nonaktifkan semua margin yang sebelumnya aktif.
                if ($request->status == 1) {

                    DB::update("
                        UPDATE margin_penjualan
                        SET status = 0,
                            updated_at = NOW()
                        WHERE status = 1
                    ");

                }

                // Membuat ID baru
                $id = DB::selectOne("
                    SELECT COALESCE(MAX(idmargin_penjualan), 0) + 1 AS idbaru
                    FROM margin_penjualan
                ");

                // Menambahkan margin baru
                DB::insert("
                    INSERT INTO margin_penjualan
                    (
                        idmargin_penjualan,
                        iduser,
                        created_at,
                        persen,
                        status,
                        updated_at
                    )
                    VALUES (?, ?, NOW(), ?, ?, NOW())
                ", [
                    $id->idbaru,
                    $request->iduser,
                    $request->persen,
                    $request->status
                ]);

            });

            return redirect()->route('margin.index')
                ->with('success', 'Margin penjualan berhasil ditambahkan!');

        } catch (\Exception $e) {

            return back()->withInput()
                ->with('error', 'Gagal menambahkan margin penjualan: ' . $e->getMessage());
        }
    }

    // FORM EDIT
    public function edit($id)
    {
        $margin = DB::selectOne(
            "SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?",
            [$id]
        );

        if (!$margin) {
            abort(404);
        }

        $user = DB::select("
            SELECT iduser, username
            FROM user
        ");

        return view('margin.edit', compact('margin', 'user'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'iduser' => 'required|integer',
            'persen' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1'
        ]);

        $cekMargin = DB::selectOne("
            SELECT idmargin_penjualan
            FROM margin_penjualan
            WHERE idmargin_penjualan = ?
        ", [$id]);

        if (!$cekMargin) {
            return redirect()->route('margin.index')
                ->with('error', 'Data margin tidak ditemukan.');
        }

        $cekUser = DB::selectOne(
            "SELECT iduser FROM user WHERE iduser = ?",
            [$request->iduser]
        );

        if (!$cekUser) {
            return back()->withInput()
                ->with('error', 'User tidak ditemukan.');
        }

        try {

            DB::transaction(function () use ($request, $id) {

                // Jika margin yang diedit akan diaktifkan,
                // nonaktifkan semua margin aktif lainnya.
                if ($request->status == 1) {

                    DB::update("
                        UPDATE margin_penjualan
                        SET status = 0,
                            updated_at = NOW()
                        WHERE status = 1
                        AND idmargin_penjualan != ?
                    ", [$id]);

                }

                // Update data margin yang dipilih
                DB::update("
                    UPDATE margin_penjualan
                    SET iduser = ?,
                        persen = ?,
                        status = ?,
                        updated_at = NOW()
                    WHERE idmargin_penjualan = ?
                ", [
                    $request->iduser,
                    $request->persen,
                    $request->status,
                    $id
                ]);

            });

            return redirect()->route('margin.index')
                ->with('success', 'Margin penjualan berhasil diperbarui!');

        } catch (\Exception $e) {

            return back()->withInput()
                ->with('error', 'Gagal memperbarui margin penjualan: ' . $e->getMessage());
        }
    }

    // HAPUS DATA
    public function destroy($id)
    {
        try {

            DB::delete("
                DELETE FROM margin_penjualan
                WHERE idmargin_penjualan = ?
            ", [$id]);

            return redirect()->route('margin.index')
                ->with('success', 'Margin penjualan berhasil dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()->route('margin.index')
                ->with('error', 'Margin tidak bisa dihapus karena masih digunakan dalam transaksi.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    // MENAMPILKAN DATA VENDOR
    public function index()
    {
        $vendor = DB::select("
            SELECT idvendor, nama_vendor, badan_hukum, status
            FROM vendor
            ORDER BY idvendor ASC
        ");

        return view('vendor.index', compact('vendor'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('vendor.create');
    }

    // MENYIMPAN DATA VENDOR
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:Y,N',
            'status' => 'required|in:A,N'
        ]);

        $id = DB::selectOne("
            SELECT COALESCE(MAX(idvendor), 0) + 1 AS idbaru
            FROM vendor
        ");

        DB::insert("
            INSERT INTO vendor
            (idvendor, nama_vendor, badan_hukum, status)
            VALUES (?, ?, ?, ?)
        ", [
            $id->idbaru,
            $request->nama_vendor,
            $request->badan_hukum,
            $request->status
        ]);

        return redirect()->route('vendor.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    // FORM EDIT
    public function edit($id)
    {
        $vendor = DB::selectOne(
            "SELECT * FROM vendor WHERE idvendor = ?",
            [$id]
        );

        if (!$vendor) {
            abort(404);
        }

        return view('vendor.edit', compact('vendor'));
    }

    // MEMPERBARUI DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:Y,N',
            'status' => 'required|in:A,N'
        ]);

        DB::update("
            UPDATE vendor
            SET nama_vendor = ?,
                badan_hukum = ?,
                status = ?
            WHERE idvendor = ?
        ", [
            $request->nama_vendor,
            $request->badan_hukum,
            $request->status,
            $id
        ]);

        return redirect()->route('vendor.index')
            ->with('success', 'Vendor berhasil diperbarui!');
    }

    // MENGHAPUS DATA
    public function destroy($id)
    {
        try {
            DB::delete(
                "DELETE FROM vendor WHERE idvendor = ?",
                [$id]
            );

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor berhasil dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()->route('vendor.index')
                ->with('error', 'Vendor tidak bisa dihapus karena masih digunakan dalam pengadaan.');
        }
    }
}

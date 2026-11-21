<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // MENAMPILKAN DATA ROLE
    public function index()
    {
        $role = DB::select("
            SELECT idrole, nama_role
            FROM `role`
            ORDER BY idrole ASC
        ");

        return view('role.index', compact('role'));
    }

    // FORM TAMBAH ROLE
    public function create()
    {
        return view('role.create');
    }

    // MENYIMPAN ROLE
    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|max:100'
        ]);

        $cek = DB::selectOne(
            "SELECT idrole FROM `role` WHERE nama_role = ?",
            [$request->nama_role]
        );

        if ($cek) {
            return back()->withInput()
                ->with('error', 'Nama role sudah tersedia.');
        }

        $id = DB::selectOne("
            SELECT COALESCE(MAX(idrole), 0) + 1 AS idbaru
            FROM `role`
        ");

        DB::insert("
            INSERT INTO `role` (idrole, nama_role)
            VALUES (?, ?)
        ", [
            $id->idbaru,
            $request->nama_role
        ]);

        return redirect()->route('role.index')
            ->with('success', 'Role berhasil ditambahkan!');
    }

    // FORM EDIT ROLE
    public function edit($id)
    {
        $role = DB::selectOne(
            "SELECT * FROM `role` WHERE idrole = ?",
            [$id]
        );

        if (!$role) {
            abort(404);
        }

        return view('role.edit', compact('role'));
    }

    // MEMPERBARUI ROLE
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|max:100'
        ]);

        $cek = DB::selectOne("
            SELECT idrole
            FROM `role`
            WHERE nama_role = ?
            AND idrole != ?
        ", [
            $request->nama_role,
            $id
        ]);

        if ($cek) {
            return back()->withInput()
                ->with('error', 'Nama role sudah digunakan.');
        }

        DB::update("
            UPDATE `role`
            SET nama_role = ?
            WHERE idrole = ?
        ", [
            $request->nama_role,
            $id
        ]);

        return redirect()->route('role.index')
            ->with('success', 'Role berhasil diperbarui!');
    }

    // MENGHAPUS ROLE
    public function destroy($id)
    {
        try {
            DB::delete("
                DELETE FROM `role`
                WHERE idrole = ?
            ", [$id]);

            return redirect()->route('role.index')
                ->with('success', 'Role berhasil dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()->route('role.index')
                ->with('error', 'Role tidak bisa dihapus karena masih digunakan oleh user.');
        }
    }
}

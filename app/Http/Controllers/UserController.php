<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // MENAMPILKAN DATA USER
    public function index()
    {
        $user = DB::select("
            SELECT
                u.iduser,
                u.username,
                r.nama_role
            FROM `user` u
            JOIN `role` r ON u.idrole = r.idrole
            ORDER BY u.iduser ASC
        ");

        return view('user.index', compact('user'));
    }

    // FORM TAMBAH USER
    public function create()
    {
        $role = DB::select("
            SELECT idrole, nama_role
            FROM `role`
            ORDER BY idrole ASC
        ");

        return view('user.create', compact('role'));
    }

    // SIMPAN USER
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:45|unique:user,username',
            'password' => 'required|min:6',
            'idrole' => 'required|integer'
        ]);

        $cekRole = DB::selectOne(
            "SELECT idrole FROM `role` WHERE idrole = ?",
            [$request->idrole]
        );

        if (!$cekRole) {
            return back()->withErrors([
                'idrole' => 'Role tidak ditemukan.'
            ])->withInput();
        }

        $id = DB::selectOne("
            SELECT COALESCE(MAX(iduser), 0) + 1 AS idbaru
            FROM `user`
        ");

        DB::insert("
            INSERT INTO `user`
            (iduser, username, password, idrole)
            VALUES (?, ?, ?, ?)
        ", [
            $id->idbaru,
            $request->username,
            Hash::make($request->password),
            $request->idrole
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    // FORM EDIT USER
    public function edit($id)
    {
        $user = DB::selectOne("
            SELECT iduser, username, idrole
            FROM `user`
            WHERE iduser = ?
        ", [$id]);

        if (!$user) {
            abort(404);
        }

        $role = DB::select("
            SELECT idrole, nama_role
            FROM `role`
            ORDER BY idrole ASC
        ");

        return view('user.edit', compact('user', 'role'));
    }

    // UPDATE USER
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|max:45|unique:user,username,' . $id . ',iduser',
            'password' => 'nullable|min:6',
            'idrole' => 'required|integer'
        ]);

        $cekRole = DB::selectOne(
            "SELECT idrole FROM `role` WHERE idrole = ?",
            [$request->idrole]
        );

        if (!$cekRole) {
            return back()->withErrors([
                'idrole' => 'Role tidak ditemukan.'
            ])->withInput();
        }

        if ($request->filled('password')) {
            DB::update("
                UPDATE `user`
                SET username = ?, password = ?, idrole = ?
                WHERE iduser = ?
            ", [
                $request->username,
                Hash::make($request->password),
                $request->idrole,
                $id
            ]);
        } else {
            DB::update("
                UPDATE `user`
                SET username = ?, idrole = ?
                WHERE iduser = ?
            ", [
                $request->username,
                $request->idrole,
                $id
            ]);
        }

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    // HAPUS USER
    public function destroy($id)
    {
        try {
            DB::delete(
                "DELETE FROM `user` WHERE iduser = ?",
                [$id]
            );

            return redirect()->route('user.index')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('user.index')
                ->with('error', 'User tidak dapat dihapus karena masih digunakan pada transaksi atau data lain.');
        }
    }
}

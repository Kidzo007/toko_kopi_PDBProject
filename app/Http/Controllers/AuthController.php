<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // MENAMPILKAN HALAMAN LOGIN
    public function showLogin()
    {
        if (session()->has('iduser')) {
            return $this->redirectDashboard(session('nama_role'));
        }

        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Mencari user berdasarkan username
        $user = DB::selectOne("
            SELECT
                u.iduser,
                u.username,
                u.password,
                u.idrole,
                r.nama_role
            FROM `user` u
            JOIN `role` r ON u.idrole = r.idrole
            WHERE u.username = ?
        ", [$request->username]);

        // Jika username tidak ditemukan
        if (!$user) {
            return back()->with('error', 'Username atau password salah!')
                         ->withInput();
        }

        // Memeriksa apakah password berbentuk hash
        $isHashed = str_starts_with($user->password, '$2y$')
                 || str_starts_with($user->password, '$argon2');

        if ($isHashed) {
            $passwordValid = Hash::check(
                $request->password,
                $user->password
            );
        } else {
            // Memeriksa password lama berbentuk teks biasa
            $passwordValid = hash_equals(
                (string) $user->password,
                (string) $request->password
            );
        }

        // Jika password salah
        if (!$passwordValid) {
            return back()->with('error', 'Username atau password salah!')
                         ->withInput();
        }

        // Jika password lama masih teks biasa,
        // ubah menjadi hash setelah login berhasil.
        if (!$isHashed) {
            DB::update("
                UPDATE `user`
                SET password = ?
                WHERE iduser = ?
            ", [
                Hash::make($request->password),
                $user->iduser
            ]);
        }

        // Membuat session login
        $request->session()->regenerate();

        session([
            'iduser' => $user->iduser,
            'username' => $user->username,
            'idrole' => $user->idrole,
            'nama_role' => $user->nama_role
        ]);

        // Mengarahkan ke dashboard sesuai role
        return $this->redirectDashboard($user->nama_role);
    }

    // REDIRECT DASHBOARD BERDASARKAN ROLE
    private function redirectDashboard($role)
    {
        switch ($role) {
            case 'Administrator':
                return redirect()->route('dashboard.administrator');

            case 'Kasir':
                return redirect()->route('dashboard.kasir');

            case 'Gudang':
                return redirect()->route('dashboard.gudang');

            case 'Manager':
                return redirect()->route('dashboard.manager');

            case 'Staff Pembelian':
                return redirect()->route('dashboard.pembelian');

            default:
                session()->flush();

                return redirect()->route('login')
                    ->with('error', 'Role akun tidak dikenali!');
        }
    }

    // PROSES LOGOUT
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout!');
    }
}

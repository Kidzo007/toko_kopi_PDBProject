<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Toko Kopi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
        }

        .navbar {
            background-color: #3e2723;
        }

        .navbar-brand {
            font-size: 23px;
            font-weight: bold;
        }

        .page-title {
            color: #3e2723;
            font-size: 32px;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background-color: #5d4037;
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            border: none;
        }

        .card-body {
            padding: 25px;
        }

        .btn-simpan {
            background-color: #5d4037;
            color: white;
        }

        .btn-simpan:hover {
            background-color: #3e2723;
            color: white;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark shadow-sm">
    <div class="container-fluid px-4">
        <span class="navbar-brand text-white">
            ☕ TOKO KOPI - MASTER USER
        </span>

        <span class="text-white">
            {{ session('username') }}
        </span>
    </div>
</nav>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="page-title mb-2">Edit User</h2>
        <p class="text-muted mb-0">
            Perbarui informasi pengguna sistem toko kopi.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            Form Edit User
        </div>

        <div class="card-body">

            <form action="{{ route('user.update', $user->iduser) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>

                    <input type="text"
                           name="username"
                           class="form-control"
                           value="{{ old('username', $user->username) }}"
                           placeholder="Masukkan username"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru</label>

                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Masukkan password baru">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti password.
                    </small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Role</label>

                    <select name="idrole" class="form-select" required>

                        @foreach($role as $r)
                            <option value="{{ $r->idrole }}"
                                {{ old('idrole', $user->idrole) == $r->idrole ? 'selected' : '' }}>
                                {{ $r->nama_role }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <button type="submit" class="btn btn-simpan">
                    Update User
                </button>

                <a href="{{ route('user.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>

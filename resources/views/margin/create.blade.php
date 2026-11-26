<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Margin - Toko Kopi</title>

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
            ☕ TOKO KOPI - MASTER MARGIN PENJUALAN
        </span>

        <span class="text-white">
            {{ session('username') }}
        </span>
    </div>
</nav>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="page-title mb-2">Tambah Margin Penjualan</h2>
        <p class="text-muted mb-0">
            Masukkan informasi margin penjualan baru.
        </p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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
            Form Tambah Margin Penjualan
        </div>

        <div class="card-body">

            <form action="{{ route('margin.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama User</label>

                    <select name="iduser" class="form-select" required>
                        <option value="">-- Pilih User --</option>

                        @foreach($user as $u)
                            <option value="{{ $u->iduser }}"
                                {{ old('iduser') == $u->iduser ? 'selected' : '' }}>
                                {{ $u->username }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Persentase Margin (%)
                    </label>

                    <input type="number"
                           name="persen"
                           class="form-control"
                           step="0.01"
                           min="0"
                           max="100"
                           value="{{ old('persen') }}"
                           placeholder="Contoh: 15.00"
                           required>

                    <small class="text-muted">
                        Masukkan persentase antara 0 sampai 100.
                    </small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>

                    <select name="status" class="form-select" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-simpan">
                    Simpan Margin
                </button>

                <a href="{{ route('margin.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Toko Kopi</title>

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
            ☕ TOKO KOPI - MASTER BARANG
        </span>

        <span class="text-white">
            {{ session('username') }}
        </span>
    </div>
</nav>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="page-title mb-2">Edit Barang</h2>
        <p class="text-muted mb-0">
            Perbarui informasi barang yang sudah terdaftar.
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
            Form Edit Barang
        </div>

        <div class="card-body">

            <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">ID Barang</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $barang->idbarang }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Barang</label>

                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama', $barang->nama) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Barang</label>

                    <select name="jenis" class="form-select" required>

                        <option value="J" {{ old('jenis', $barang->jenis) == 'J' ? 'selected' : '' }}>
                            Produk Jadi
                        </option>

                        <option value="B" {{ old('jenis', $barang->jenis) == 'B' ? 'selected' : '' }}>
                            Bahan Baku
                        </option>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Satuan</label>

                    <select name="idsatuan" class="form-select" required>

                        @foreach($satuan as $s)
                            <option value="{{ $s->idsatuan }}"
                                {{ old('idsatuan', $barang->idsatuan) == $s->idsatuan ? 'selected' : '' }}>
                                {{ $s->nama_satuan }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga</label>

                    <input type="number"
                           name="harga"
                           class="form-control"
                           min="0"
                           value="{{ old('harga', $barang->harga) }}"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>

                    <select name="status" class="form-select" required>

                        <option value="1" {{ old('status', $barang->status) == 1 ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0" {{ old('status', $barang->status) == 0 ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>
                </div>

                <button type="submit" class="btn btn-simpan">
                    Update Barang
                </button>

                <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>

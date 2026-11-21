<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Toko Kopi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">Tambah Barang</h3>
            <p class="text-muted mb-4">Masukkan informasi barang baru.</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
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

            <form action="{{ route('barang.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Barang</label>
                    <select name="jenis" class="form-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="J" {{ old('jenis') == 'J' ? 'selected' : '' }}>
                            Produk Jadi
                        </option>
                        <option value="B" {{ old('jenis') == 'B' ? 'selected' : '' }}>
                            Bahan Baku
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="idsatuan" class="form-select" required>
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuan as $s)
                            <option value="{{ $s->idsatuan }}"
                                {{ old('idsatuan') == $s->idsatuan ? 'selected' : '' }}>
                                {{ $s->nama_satuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control"
                           min="0" value="{{ old('harga') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Barang
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>

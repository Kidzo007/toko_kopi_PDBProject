<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Toko Kopi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">Edit Barang</h3>
            <p class="text-muted mb-4">Perbarui informasi barang.</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID Barang</label>
                    <input type="text" class="form-control"
                           value="{{ $barang->idbarang }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $barang->nama) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Barang</label>
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
                    <label class="form-label">Satuan</label>
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
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control"
                           min="0"
                           value="{{ old('harga', $barang->harga) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ old('status', $barang->status) == 1 ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0" {{ old('status', $barang->status) == 0 ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>

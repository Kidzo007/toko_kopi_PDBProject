<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Satuan - Toko Kopi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">Edit Satuan</h3>
            <p class="text-muted mb-4">Perbarui informasi satuan barang.</p>

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

            <form action="{{ route('satuan.update', $satuan->idsatuan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID Satuan</label>
                    <input type="text" class="form-control"
                           value="{{ $satuan->idsatuan }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Satuan</label>
                    <input type="text"
                           name="nama_satuan"
                           class="form-control"
                           value="{{ old('nama_satuan', $satuan->nama_satuan) }}"
                           maxlength="45"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ old('status', $satuan->status) == 1 ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0" {{ old('status', $satuan->status) == 0 ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('satuan.index') }}" class="btn btn-secondary">
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

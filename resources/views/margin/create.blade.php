<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Margin - Toko Kopi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm border-0 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">Tambah Margin Penjualan</h3>
            <p class="text-muted mb-4">
                Masukkan informasi margin penjualan baru.
            </p>

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

            <form action="{{ route('margin.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama User</label>

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
                    <label class="form-label">Persentase Margin (%)</label>

                    <input
                        type="number"
                        name="persen"
                        class="form-control"
                        step="0.01"
                        min="0"
                        max="100"
                        value="{{ old('persen') }}"
                        placeholder="Contoh: 15.00"
                        required
                    >

                    <small class="text-muted">
                        Masukkan persentase antara 0 sampai 100.
                    </small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('margin.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Margin
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>

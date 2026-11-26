<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor - Toko Kopi</title>

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
            ☕ TOKO KOPI - MASTER VENDOR
        </span>

        <span class="text-white">
            {{ session('username') }}
        </span>
    </div>
</nav>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="page-title mb-2">Edit Vendor</h2>
        <p class="text-muted mb-0">
            Perbarui informasi vendor yang sudah terdaftar.
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
            Form Edit Vendor
        </div>

        <div class="card-body">

            <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">ID Vendor</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $vendor->idvendor }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Vendor</label>

                    <input type="text"
                           name="nama_vendor"
                           class="form-control"
                           value="{{ old('nama_vendor', $vendor->nama_vendor) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Badan Hukum</label>

                    <select name="badan_hukum" class="form-select" required>

                        <option value="Y" {{ old('badan_hukum', $vendor->badan_hukum) == 'Y' ? 'selected' : '' }}>
                            Ya, Berbadan Hukum
                        </option>

                        <option value="N" {{ old('badan_hukum', $vendor->badan_hukum) == 'N' ? 'selected' : '' }}>
                            Tidak Berbadan Hukum
                        </option>

                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>

                    <select name="status" class="form-select" required>

                        <option value="A" {{ old('status', $vendor->status) == 'A' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="N" {{ old('status', $vendor->status) == 'N' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>
                </div>

                <button type="submit" class="btn btn-simpan">
                    Update Vendor
                </button>

                <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>

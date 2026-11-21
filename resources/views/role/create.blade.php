<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Role - Toko Kopi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">Tambah Role</h3>
            <p class="text-muted mb-4">Tambahkan role pengguna baru.</p>

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

            <form action="{{ route('role.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Nama Role</label>
                    <input type="text"
                           name="nama_role"
                           class="form-control"
                           value="{{ old('nama_role') }}"
                           placeholder="Contoh: Supervisor"
                           maxlength="100"
                           required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('role.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Role
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>

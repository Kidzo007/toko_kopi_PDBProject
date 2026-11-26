<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Role - Toko Kopi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #3e2723;
        }

        .page-title {
            color: #3e2723;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card-header {
            background: #5d4037;
            color: white;
            font-weight: bold;
        }

        .table thead th {
            background: #5d4037;
            color: white;
            vertical-align: middle;
        }

        .btn-coffee {
            background: #5d4037;
            color: white;
        }

        .btn-coffee:hover {
            background: #3e2723;
            color: white;
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}

    <nav class="navbar navbar-dark">
        <div class="container-fluid px-4">

            <span class="navbar-brand fw-bold">
                ☕ TOKO KOPI - MASTER ROLE
            </span>

            <span class="text-white">
                {{ session('username') }}
            </span>

        </div>
    </nav>


    <div class="container-fluid px-4 py-4">

        {{-- HEADER --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="page-title">Data Role</h2>

                <p class="text-muted mb-0">
                    Manajemen hak akses pengguna toko kopi.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('dashboard.administrator') }}"
                    class="btn btn-secondary">
                    ← Kembali ke Dashboard
                </a>

                <a href="{{ route('role.create') }}"
                    class="btn btn-coffee">
                    + Tambah Role
                </a>

            </div>

        </div>


        {{-- NOTIFIKASI --}}

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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


        {{-- TABEL ROLE --}}

        <div class="card">

            <div class="card-header py-3">
                Daftar Master Role
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="80">No.</th>
                                <th>Nama Role</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($role as $index => $r)

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-semibold">
                                        {{ $r->nama_role }}
                                    </td>

                                    <td>

                                        <a href="{{ route('role.edit', $r->idrole) }}"
                                            class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('role.destroy', $r->idrole) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus role ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>

                                        </form>

                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Belum ada data role.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="text-muted small">
                    Total role: {{ count($role) }} data
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

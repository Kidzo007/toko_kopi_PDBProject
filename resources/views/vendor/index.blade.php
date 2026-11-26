<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Vendor - Toko Kopi</title>

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
            font-size: 36px;
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
            padding: 20px;
        }

        .table thead th {
            background-color: #5d4037;
            color: white;
            vertical-align: middle;
        }

        .table td,
        .table th {
            padding: 12px;
        }

        .btn-tambah {
            background-color: #5d4037;
            color: white;
        }

        .btn-tambah:hover {
            background-color: #3e2723;
            color: white;
        }
    </style>
</head>

<body>

@php
    $role = session('nama_role');

    $dashboard = match($role) {
        'Administrator' => route('dashboard.administrator'),
        'Gudang' => route('dashboard.gudang'),
        'Manager' => route('dashboard.manager'),
        'Staff Pembelian' => route('dashboard.pembelian'),
        default => route('dashboard.administrator')
    };

    $bolehKelola = in_array($role, [
        'Administrator',
        'Gudang',
        'Staff Pembelian'
    ]);
@endphp

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

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="page-title mb-2">Data Vendor</h2>
            <p class="text-muted mb-0">
                Manajemen master vendor toko kopi.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ $dashboard }}" class="btn btn-secondary">
                ← Kembali ke Dashboard
            </a>

            @if($bolehKelola)
                <a href="{{ route('vendor.create') }}" class="btn btn-tambah">
                    + Tambah Vendor
                </a>
            @endif

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
            Daftar Master Vendor
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-3">

                    <thead>
                        <tr>
                            <th width="7%">No.</th>
                            <th>Nama Vendor</th>
                            <th>Badan Hukum</th>
                            <th width="18%">Status</th>

                            @if($bolehKelola)
                                <th width="15%">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($vendor as $index => $v)

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>{{ $v->nama_vendor }}</td>

                                <td>
                                    @if($v->badan_hukum == 'Y')
                                        Berbadan Hukum
                                    @else
                                        Tidak Berbadan Hukum
                                    @endif
                                </td>

                                <td>
                                    @if($v->status == 'A')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>

                                @if($bolehKelola)
                                    <td>
                                        <a href="{{ route('vendor.edit', $v->idvendor) }}"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('vendor.destroy', $v->idvendor) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus vendor ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>

                                        </form>
                                    </td>
                                @endif

                            </tr>

                        @empty

                            <tr>
                                <td colspan="{{ $bolehKelola ? 5 : 4 }}"
                                    class="text-center text-muted">
                                    Belum ada data vendor.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <p class="text-muted mb-0">
                Total vendor: <strong>{{ count($vendor) }} data</strong>
            </p>

        </div>
    </div>

</div>

</body>
</html>

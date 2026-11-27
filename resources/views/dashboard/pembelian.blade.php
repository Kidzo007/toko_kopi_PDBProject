<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Pembelian - Toko Kopi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #3e2723;
        }

        .dashboard-title {
            color: #3e2723;
            font-weight: bold;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
        }

        .card-header {
            background: #5d4037;
            color: white;
            font-weight: bold;
        }

        .menu-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-3px);
        }

        .menu-icon {
            font-size: 35px;
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}

<nav class="navbar navbar-dark">
    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            ☕ TOKO KOPI - STAFF PEMBELIAN
        </span>

        <div class="d-flex align-items-center gap-3">

            <span class="text-white">
                {{ session('username') }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>

        </div>

    </div>
</nav>


<div class="container-fluid px-4 py-4">

    {{-- JUDUL --}}

    <div class="mb-4">
        <h2 class="dashboard-title">
            Dashboard Staff Pembelian
        </h2>

        <p class="text-muted">
            Selamat datang, {{ session('username') }}.
            Berikut ringkasan aktivitas pengadaan barang.
        </p>
    </div>


    {{-- MENU PENGELOLAAN PENGADAAN --}}

    <h5 class="mb-3">Menu Pengelolaan Pengadaan</h5>

    <div class="row g-3 mb-4">

        {{-- DATA PENGADAAN --}}

        <div class="col-md-4">
            <div class="card menu-card h-100">
                <div class="card-body p-4">

                    <div class="menu-icon mb-2">📋</div>

                    <h5 class="fw-bold">Data Pengadaan</h5>

                    <p class="text-muted">
                        Melihat seluruh riwayat transaksi pengadaan
                        barang dari vendor.
                    </p>

                    <a href="{{ route('pengadaan.index') }}"
                        class="btn btn-primary">
                        Lihat Data Pengadaan
                    </a>

                </div>
            </div>
        </div>


        {{-- TAMBAH PENGADAAN --}}

        <div class="col-md-4">
            <div class="card menu-card h-100">
                <div class="card-body p-4">

                    <div class="menu-icon mb-2">➕</div>

                    <h5 class="fw-bold">Tambah Pengadaan</h5>

                    <p class="text-muted">
                        Membuat transaksi pengadaan barang baru
                        dari vendor.
                    </p>

                    <a href="{{ route('pengadaan.create') }}"
                        class="btn btn-success">
                        Tambah Pengadaan
                    </a>

                </div>
            </div>
        </div>


        {{-- DETAIL PENGADAAN SP --}}

        <div class="col-md-4">
            <div class="card menu-card h-100">
                <div class="card-body p-4">

                    <div class="menu-icon mb-2">📑</div>

                    <h5 class="fw-bold">Detail Pengadaan (SP)</h5>

                    <p class="text-muted">
                        Melihat detail transaksi pengadaan
                        menggunakan Stored Procedure.
                    </p>

                    <a href="{{ route('pengadaan.detailSP') }}"
                        class="btn btn-info text-white">
                        Detail Pengadaan SP
                    </a>

                </div>
            </div>
        </div>

    </div>


    {{-- STATISTIK --}}

    <h5 class="mb-3">Ringkasan Pengadaan</h5>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Total Pengadaan</h6>

                    <div class="stat-number text-primary">
                        {{ number_format($totalPengadaan) }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Pengadaan Hari Ini</h6>

                    <div class="stat-number text-info">
                        {{ number_format($pengadaanHariIni) }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Total Vendor</h6>

                    <div class="stat-number text-success">
                        {{ number_format($totalVendor) }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Total Nilai Pengadaan</h6>

                    <div class="stat-number text-warning">
                        Rp {{ number_format($totalNilaiPengadaan, 0, ',', '.') }}
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- RIWAYAT PENGADAAN --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">
            Riwayat Pengadaan Terbaru
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pengadaanTerbaru as $p)

                            <tr>
                                <td>{{ $p->idpengadaan }}</td>

                                <td>{{ $p->timestamp }}</td>

                                <td>{{ $p->nama_vendor }}</td>

                                <td>{{ $p->username }}</td>

                                <td>{{ $p->status }}</td>

                                <td>
                                    Rp {{ number_format($p->total_nilai, 0, ',', '.') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada data pengadaan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- DETAIL BARANG PEMBELIAN --}}

    <div class="card shadow-sm">

        <div class="card-header">
            Detail Barang yang Dibeli
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($detailPembelian as $d)

                            <tr>
                                <td>{{ $d->idpengadaan }}</td>

                                <td>{{ $d->timestamp }}</td>

                                <td>{{ $d->nama_barang }}</td>

                                <td>{{ number_format($d->jumlah) }}</td>

                                <td>
                                    Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($d->sub_total, 0, ',', '.') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada detail pembelian barang.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>

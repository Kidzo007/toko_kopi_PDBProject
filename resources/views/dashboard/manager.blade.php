<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Manager - Toko Kopi</title>

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
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
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

        /* TOMBOL MONITORING AGAR SEJAJAR */
        .menu-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>

</head>

<body>

{{-- NAVBAR --}}

<nav class="navbar navbar-dark">
    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            ☕ TOKO KOPI - MANAGER
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
            Dashboard Manager
        </h2>

        <p class="text-muted">
            Selamat datang, {{ session('username') }}.
            Berikut ringkasan operasional Toko Kopi.
        </p>

    </div>


    {{-- MENU MONITORING STOK --}}

    <h5 class="mb-3">
        Menu Monitoring
    </h5>

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card stat-card h-100">

                <div class="card-body p-4">

                    <div style="font-size: 35px;">
                        📊
                    </div>

                    <h5 class="fw-bold">
                        Kartu Stok
                    </h5>

                    <p class="text-muted">
                        Memantau pergerakan stok barang,
                        barang masuk, dan barang keluar.
                    </p>

                    {{-- TOMBOL SEJAJAR --}}

                    <div class="menu-buttons">

                        <a href="{{ route('kartu_stok.index') }}"
                           class="btn btn-primary">
                            Buka Kartu Stok
                        </a>

                        <a href="{{ route('kartu_stok.stok') }}"
                           class="btn btn-success">
                            Lihat Stok Barang
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- STATISTIK MASTER --}}

    <h5 class="mb-3">
        Statistik Master
    </h5>

    <div class="row g-3 mb-4">

        <div class="col-md-4 col-lg-4">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Barang
                    </h6>

                    <div class="stat-number text-primary">
                        {{ number_format($totalBarang) }}
                    </div>

                    <small class="text-muted">
                        Data barang terdaftar
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-4">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Vendor
                    </h6>

                    <div class="stat-number text-success">
                        {{ number_format($totalVendor) }}
                    </div>

                    <small class="text-muted">
                        Data vendor terdaftar
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-4 col-lg-4">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total User
                    </h6>

                    <div class="stat-number text-warning">
                        {{ number_format($totalUser) }}
                    </div>

                    <small class="text-muted">
                        Pengguna sistem
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- STATISTIK TRANSAKSI --}}

    <h5 class="mb-3">
        Statistik Transaksi
    </h5>

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Penjualan
                    </h6>

                    <div class="stat-number text-primary">
                        {{ number_format($totalPenjualan) }}
                    </div>

                    <small class="text-muted">
                        Jumlah transaksi penjualan
                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Pengadaan
                    </h6>

                    <div class="stat-number text-success">
                        {{ number_format($totalPengadaan) }}
                    </div>

                    <small class="text-muted">
                        Jumlah transaksi pengadaan
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- NILAI TRANSAKSI --}}

    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Nilai Penjualan
                    </h6>

                    <h3 class="text-success fw-bold">
                        Rp {{ number_format($pendapatan, 0, ',', '.') }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card stat-card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Nilai Pengadaan
                    </h6>

                    <h3 class="text-danger fw-bold">
                        Rp {{ number_format($nilaiPengadaan, 0, ',', '.') }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- TABEL PENJUALAN TERBARU --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">
            Penjualan Terbaru
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($penjualanTerbaru as $p)

                            <tr>

                                <td>
                                    {{ $p->idpenjualan }}
                                </td>

                                <td>
                                    {{ $p->created_at }}
                                </td>

                                <td>
                                    {{ $p->username }}
                                </td>

                                <td>
                                    Rp {{ number_format($p->total_nilai, 0, ',', '.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    Belum ada transaksi penjualan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- TABEL PENGADAAN TERBARU --}}

    <div class="card shadow-sm">

        <div class="card-header">
            Pengadaan Terbaru
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Total</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($pengadaanTerbaru as $p)

                            <tr>

                                <td>
                                    {{ $p->idpengadaan }}
                                </td>

                                <td>
                                    {{ $p->timestamp }}
                                </td>

                                <td>
                                    {{ $p->nama_vendor }}
                                </td>

                                <td>
                                    Rp {{ number_format($p->total_nilai, 0, ',', '.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    Belum ada transaksi pengadaan.
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

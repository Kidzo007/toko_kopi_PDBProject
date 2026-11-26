<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Kasir - Toko Kopi</title>

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
    </style>
</head>

<body>

{{-- NAVBAR --}}

<nav class="navbar navbar-dark">
    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            ☕ TOKO KOPI - KASIR
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

    <div class="mb-4">
        <h2 class="dashboard-title">
            Dashboard Kasir
        </h2>

        <p class="text-muted">
            Selamat datang, {{ session('username') }}.
            Berikut ringkasan aktivitas penjualan toko kopi.
        </p>
    </div>


    {{-- STATISTIK PENJUALAN --}}

    <h5 class="mb-3">Ringkasan Penjualan</h5>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Total Transaksi</h6>

                    <div class="stat-number text-primary">
                        {{ number_format($totalTransaksi) }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Transaksi Hari Ini</h6>

                    <div class="stat-number text-info">
                        {{ number_format($transaksiHariIni) }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Penjualan Hari Ini</h6>

                    <div class="stat-number text-success">
                        {{ number_format($penjualanHariIni) }}
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">

                    <h6 class="text-muted">Total Pendapatan</h6>

                    <div class="stat-number text-warning">
                        {{ number_format($totalPendapatan) }}
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- TRANSAKSI TERBARU --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">
            Transaksi Penjualan Terbaru
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Nama Kasir</th>
                            <th>Total Penjualan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($transaksiTerbaru as $transaksi)

                            <tr>
                                <td>{{ $transaksi->idpenjualan }}</td>

                                <td>{{ $transaksi->created_at }}</td>

                                <td>{{ $transaksi->username }}</td>

                                <td>
                                    Rp {{ number_format($transaksi->total_nilai, 0, ',', '.') }}
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


    {{-- DAFTAR BARANG --}}

    <div class="card shadow-sm">

        <div class="card-header">
            Daftar Barang Tersedia
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($daftarBarang as $barang)

                            <tr>
                                <td>{{ $barang->idbarang }}</td>

                                <td>{{ $barang->nama }}</td>

                                <td>{{ $barang->nama_satuan }}</td>

                                <td>
                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    Belum ada barang aktif.
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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Gudang - Toko Kopi</title>

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

<nav class="navbar navbar-dark">
    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            ☕ TOKO KOPI - GUDANG
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
        <h2 class="dashboard-title">Dashboard Gudang</h2>

        <p class="text-muted">
            Selamat datang, {{ session('username') }}.
            Pantau persediaan dan pergerakan stok barang.
        </p>
    </div>


    {{-- STATISTIK STOK --}}

    <h5 class="mb-3">Ringkasan Persediaan</h5>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang</h6>

                    <div class="stat-number text-primary">
                        {{ number_format($totalBarang) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Total Stok</h6>

                    <div class="stat-number text-success">
                        {{ number_format($totalStok) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Barang Masuk</h6>

                    <div class="stat-number text-info">
                        {{ number_format($totalMasuk) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="text-muted">Barang Keluar</h6>

                    <div class="stat-number text-danger">
                        {{ number_format($totalKeluar) }}
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- DAFTAR STOK --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">
            Daftar Persediaan Barang
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Status Stok</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($daftarStok as $barang)

                            <tr>
                                <td>{{ $barang->idbarang }}</td>

                                <td>{{ $barang->nama }}</td>

                                <td>{{ $barang->nama_satuan }}</td>

                                <td>{{ number_format($barang->stok) }}</td>

                                <td>
                                    @if($barang->stok <= 5)
                                        <span class="badge bg-danger">
                                            Stok Menipis
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Stok Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    Belum ada data barang.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- PERINGATAN STOK MENIPIS --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-danger">
            Peringatan Stok Menipis
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead class="table-dark">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Sisa Stok</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($stokMenipis as $barang)

                            <tr>
                                <td>{{ $barang->nama }}</td>
                                <td>{{ $barang->nama_satuan }}</td>
                                <td class="text-danger fw-bold">
                                    {{ number_format($barang->stok) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="text-center text-success">
                                    Semua stok barang dalam kondisi aman.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- RIWAYAT STOK --}}

    <div class="card shadow-sm">

        <div class="card-header">
            Riwayat Pergerakan Stok Terbaru
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>ID Kartu Stok</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jenis Transaksi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok Akhir</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($riwayatStok as $stok)

                            <tr>
                                <td>{{ $stok->idkartu_stok }}</td>

                                <td>{{ $stok->created_at }}</td>

                                <td>{{ $stok->nama }}</td>

                                <td>{{ $stok->jenis_transaksi }}</td>

                                <td class="text-success">
                                    {{ number_format($stok->masuk) }}
                                </td>

                                <td class="text-danger">
                                    {{ number_format($stok->keluar) }}
                                </td>

                                <td>
                                    {{ number_format($stok->stock) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada riwayat pergerakan stok.
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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Administrator - Toko Kopi</title>

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

        .menu-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.2s;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-4px);
        }

        .menu-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .card-title {
            color: #3e2723;
            font-weight: bold;
        }

        .section-title {
            color: #5d4037;
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}

    <nav class="navbar navbar-dark">
        <div class="container-fluid px-4">

            <span class="navbar-brand fw-bold">
                ☕ TOKO KOPI - ADMINISTRATOR
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

        {{-- JUDUL DASHBOARD --}}

        <div class="mb-4">
            <h2 class="dashboard-title">
                Dashboard Administrator
            </h2>

            <p class="text-muted">
                Selamat datang, {{ session('username') }}!
                Anda memiliki akses penuh untuk mengelola sistem Toko Kopi.
            </p>
        </div>


        {{-- MASTER DATA --}}

        <h5 class="section-title mb-3">
            Master Data
        </h5>

        <div class="row g-3 mb-4">

            {{-- BARANG --}}

            <div class="col-md-4">
                <div class="card menu-card">
                    <div class="card-body p-4">

                        <div class="menu-icon">📦</div>

                        <h5 class="card-title">Master Barang</h5>

                        <p class="text-muted">
                            Mengelola data barang, nama produk,
                            harga, jenis, dan satuan barang.
                        </p>

                        <a href="{{ route('barang.index') }}"
                            class="btn btn-primary">
                            Kelola Data Barang
                        </a>

                    </div>
                </div>
            </div>


            {{-- MARGIN PENJUALAN --}}

            <div class="col-md-4">
                <div class="card menu-card">
                    <div class="card-body p-4">

                        <div class="menu-icon">💰</div>

                        <h5 class="card-title">Margin Penjualan</h5>

                        <p class="text-muted">
                            Mengatur persentase margin untuk
                            menentukan harga jual produk.
                        </p>

                        <a href="{{ route('margin.index') }}"
                            class="btn btn-primary">
                            Kelola Margin
                        </a>

                    </div>
                </div>
            </div>


            {{-- ROLE --}}

            <div class="col-md-4">
                <div class="card menu-card">
                    <div class="card-body p-4">

                        <div class="menu-icon">🔐</div>

                        <h5 class="card-title">Master Role</h5>

                        <p class="text-muted">
                            Mengelola role dan hak akses
                            pengguna dalam sistem.
                        </p>

                        <a href="{{ route('role.index') }}"
                            class="btn btn-primary">
                            Kelola Role
                        </a>

                    </div>
                </div>
            </div>


            {{-- SATUAN --}}

            <div class="col-md-4">
                <div class="card menu-card">
                    <div class="card-body p-4">

                        <div class="menu-icon">📏</div>

                        <h5 class="card-title">Master Satuan</h5>

                        <p class="text-muted">
                            Mengelola satuan barang seperti
                            pcs, gram, kilogram, dan liter.
                        </p>

                        <a href="{{ route('satuan.index') }}"
                            class="btn btn-primary">
                            Kelola Satuan
                        </a>

                    </div>
                </div>
            </div>


            {{-- VENDOR --}}

            <div class="col-md-4">
                <div class="card menu-card">
                    <div class="card-body p-4">

                        <div class="menu-icon">🏢</div>

                        <h5 class="card-title">Master Vendor</h5>

                        <p class="text-muted">
                            Mengelola informasi vendor atau
                            pemasok barang toko kopi.
                        </p>

                        <a href="{{ route('vendor.index') }}"
                            class="btn btn-primary">
                            Kelola Vendor
                        </a>

                    </div>
                </div>
            </div>


            {{-- USER --}}

            <div class="col-md-4">
                <div class="card menu-card">
                    <div class="card-body p-4">

                        <div class="menu-icon">👤</div>

                        <h5 class="card-title">Master User</h5>

                        <p class="text-muted">
                            Mengelola akun pengguna,
                            username, password, dan role.
                        </p>

                        <a href="{{ route('user.index') }}"
                            class="btn btn-primary">
                            Kelola User
                        </a>

                    </div>
                </div>
            </div>

        </div>


        {{-- INFORMASI --}}

        <div class="alert alert-info shadow-sm">
            <strong>Informasi Administrator</strong>

            <p class="mb-0 mt-1">
                Administrator memiliki akses untuk mengelola
                seluruh data master dan pengaturan sistem
                sesuai hak akses yang telah ditentukan.
            </p>
        </div>

    </div>

</body>
</html>

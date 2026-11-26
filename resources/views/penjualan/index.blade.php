<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - Toko Kopi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f3ef;
        }

        .navbar {
            background-color: #4b2e20;
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .judul {
            color: #4b2e20;
            font-weight: bold;
        }

        .harga {
            color: #198754;
            font-weight: bold;
        }

        .btn-kopi {
            background-color: #4b2e20;
            color: white;
        }

        .btn-kopi:hover {
            background-color: #65412f;
            color: white;
        }
    </style>
</head>
<script>
    let keranjang = [];

    // Format angka menjadi Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    // Menambahkan barang ke keranjang
    function tambahKeranjang(id, nama, harga) {

        const inputJumlah = document.getElementById('jumlah_' + id);
        const jumlah = parseInt(inputJumlah.value);

        if (!Number.isInteger(jumlah) || jumlah < 1) {
            alert('Jumlah barang harus minimal 1.');
            return;
        }

        const barangAda = keranjang.find(item => item.id === id);

        if (barangAda) {
            barangAda.jumlah += jumlah;
        } else {
            keranjang.push({
                id: id,
                nama: nama,
                harga: Number(harga),
                jumlah: jumlah
            });
        }

        inputJumlah.value = 1;

        tampilkanKeranjang();
    }

    // Menampilkan isi keranjang
    function tampilkanKeranjang() {

        const tbody = document.getElementById('keranjang');

        tbody.innerHTML = '';

        let total = 0;

        if (keranjang.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Keranjang masih kosong.
                    </td>
                </tr>
            `;
        }

        keranjang.forEach((item, index) => {

            const subtotal = item.harga * item.jumlah;

            total += subtotal;

            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>

                    <td>${escapeHTML(item.nama)}</td>

                    <td>${formatRupiah(item.harga)}</td>

                    <td>
                        <input
                            type="number"
                            min="1"
                            value="${item.jumlah}"
                            class="form-control"
                            style="width: 90px"
                            onchange="ubahJumlah(${item.id}, this.value)">
                    </td>

                    <td class="harga">
                        ${formatRupiah(subtotal)}
                    </td>

                    <td>
                        <button
                            class="btn btn-danger btn-sm"
                            onclick="hapusBarang(${item.id})">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('totalBelanja').textContent =
            formatRupiah(total);
    }

    // Mencegah nama barang dimasukkan sebagai HTML
    function escapeHTML(teks) {
        return String(teks).replace(/[&<>"']/g, function (karakter) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[karakter];
        });
    }

    // Mengubah jumlah barang
    function ubahJumlah(id, jumlah) {

        jumlah = parseInt(jumlah);

        if (!Number.isInteger(jumlah) || jumlah < 1) {
            alert('Jumlah minimal adalah 1.');
            tampilkanKeranjang();
            return;
        }

        const barang = keranjang.find(item => item.id === id);

        if (barang) {
            barang.jumlah = jumlah;
        }

        tampilkanKeranjang();
    }

    // Menghapus barang
    function hapusBarang(id) {

        keranjang = keranjang.filter(item => item.id !== id);

        tampilkanKeranjang();
    }
</script>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <span class="navbar-brand">☕ TOKO KOPI</span>

        <a href="{{ route('dashboard.kasir') }}"
           class="btn btn-outline-light btn-sm">
            Kembali ke Dashboard
        </a>
    </div>
</nav>

<div class="container py-4">

    <div class="mb-4">
        <h3 class="judul">Transaksi Penjualan</h3>
        <p class="text-muted">
            Pilih barang yang ingin dibeli untuk memulai transaksi.
        </p>
    </div>

    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <small class="text-muted">Kasir</small>
                <h5 class="mb-0">
                    {{ session('username') ?? 'Kasir' }}
                </h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <small class="text-muted">Margin Penjualan</small>
                <h5 class="mb-0">
                    {{ number_format($persenMargin, 2) }}%
                </h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <small class="text-muted">Tanggal Transaksi</small>
                <h5 class="mb-0">
                    {{ date('d-m-Y') }}
                </h5>
            </div>
        </div>

    </div>

    <div class="card shadow-sm p-4">

        <h5 class="judul mb-3">Daftar Barang</h5>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($barang as $index => $item)

                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>{{ $item->nama }}</td>

                        <td>{{ $item->nama_satuan }}</td>

                        <td>
                            Rp {{ number_format($item->harga_modal, 0, ',', '.') }}
                        </td>

                        <td class="harga">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </td>

                        <td style="width: 120px;">
                            <input
                                type="number"
                                class="form-control"
                                min="1"
                                value="1"
                                id="jumlah_{{ $item->idbarang }}">
                        </td>

                        <td>
                            <button
                                type="button"
                                class="btn btn-kopi btn-sm"
                                onclick="tambahKeranjang(
                                    {{ $item->idbarang }},
                                    '{{ addslashes($item->nama) }}',
                                    {{ $item->harga_jual }}
                                )">
                                Pilih Barang
                            </button>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada barang aktif.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="alert alert-info mt-4">
        <strong>Informasi:</strong>
        Daftar barang sudah terhubung dengan database.
        Fitur keranjang, perhitungan total, dan penyimpanan transaksi
        akan dibuat pada tahap berikutnya.
    </div>

    <!-- KERANJANG BELANJA -->
<div class="card shadow-sm p-4 mt-4">

    <h5 class="judul mb-3">🛒 Keranjang Belanja</h5>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">

            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="keranjang">
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Keranjang masih kosong.
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

    <div class="text-end mt-3">
        <h5>
            Total Belanja:
            <span class="harga" id="totalBelanja">Rp 0</span>
        </h5>
    </div>

</div>
</div>

</body>
</html>

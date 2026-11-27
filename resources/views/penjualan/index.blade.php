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
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #4b2e20;
            padding: 12px 0;
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 22px;
        }

        .judul {
            color: #4b2e20;
            font-weight: bold;
        }

        .harga {
            color: #198754;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .btn-kopi {
            background-color: #4b2e20;
            color: white;
        }

        .btn-kopi:hover {
            background-color: #65412f;
            color: white;
        }

        .btn-dashboard {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-dashboard:hover {
            background-color: #5c636a;
            color: white;
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <span class="navbar-brand">
            ☕ TOKO KOPI
        </span>
    </div>
</nav>


<div class="container py-4">

    {{-- JUDUL DAN TOMBOL KEMBALI --}}

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h3 class="judul mb-2">
                Transaksi Penjualan
            </h3>

            <p class="text-muted mb-0">
                Pilih barang yang ingin dibeli untuk memulai transaksi.
            </p>
        </div>

        {{-- TOMBOL KEMBALI SESUAI ROLE --}}

        @if(session('nama_role') === 'Administrator')
            <a href="{{ route('dashboard.administrator') }}"
               class="btn btn-dashboard btn-sm px-3 py-2">
                ← Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('dashboard.kasir') }}"
               class="btn btn-dashboard btn-sm px-3 py-2">
                ← Kembali ke Dashboard
            </a>
        @endif

    </div>


    {{-- NOTIFIKASI --}}

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
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif


    {{-- INFORMASI TRANSAKSI --}}

    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <small class="text-muted">Kasir</small>
                <h5 class="mb-0">
                    {{ session('username') ?? 'Kasir' }}
                </h5>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <small class="text-muted">Margin Penjualan</small>
                <h5 class="mb-0">
                    {{ number_format($persenMargin, 2) }}%
                </h5>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <small class="text-muted">Tanggal Transaksi</small>
                <h5 class="mb-0">
                    {{ date('d-m-Y') }}
                </h5>
            </div>
        </div>

    </div>


    {{-- DAFTAR BARANG --}}

    <div class="card shadow-sm p-4">

        <h5 class="judul mb-3">
            Daftar Barang
        </h5>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($barang as $index => $item)

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

                        <td>
                            <strong>{{ $item->stok }}</strong>
                        </td>

                        <td style="width:120px">
                            <input
                                type="number"
                                class="form-control"
                                min="1"
                                max="{{ $item->stok }}"
                                value="1"
                                id="jumlah_{{ $item->idbarang }}">
                        </td>

                        <td>
                            <button
                                type="button"
                                class="btn btn-kopi btn-sm"
                                {{ $item->stok <= 0 ? 'disabled' : '' }}
                                onclick="tambahKeranjang(
                                    {{ $item->idbarang }},
                                    {{ Illuminate\Support\Js::from($item->nama) }},
                                    {{ $item->harga_jual }},
                                    {{ $item->stok }}
                                )">

                                Pilih Barang

                            </button>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center">
                            Belum ada barang aktif.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- FORM PENJUALAN --}}

    <form action="{{ route('penjualan.store') }}"
          method="POST"
          id="formPenjualan">

        @csrf

        <div class="card shadow-sm p-4 mt-4">

            <h5 class="judul mb-3">
                🛒 Keranjang Belanja
            </h5>

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


            {{-- RINGKASAN PEMBAYARAN --}}

            <div class="row justify-content-end">

                <div class="col-md-5">

                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal</span>
                        <strong id="subtotalBelanja">Rp 0</strong>
                    </div>

                    <div class="mb-2 d-flex justify-content-between">
                        <span>PPN (11%)</span>
                        <strong id="ppnBelanja">Rp 0</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <h5>Total Pembayaran</h5>
                        <h5 class="harga" id="totalBelanja">Rp 0</h5>
                    </div>

                </div>

            </div>


            {{-- TOMBOL AKSI --}}

            <div class="text-end mt-4">

                <button
                    type="button"
                    class="btn btn-secondary"
                    onclick="kosongkanKeranjang()">

                    Kosongkan Keranjang

                </button>

                <button
                    type="submit"
                    class="btn btn-success"
                    id="btnSimpan">

                    Simpan Transaksi

                </button>

            </div>

        </div>

    </form>

</div>


{{-- JAVASCRIPT --}}

<script>

    let keranjang = [];


    // FORMAT RUPIAH

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }


    // TAMBAH BARANG

    function tambahKeranjang(id, nama, harga, stok) {

        const inputJumlah = document.getElementById('jumlah_' + id);

        const jumlah = parseInt(inputJumlah.value);

        if (!Number.isInteger(jumlah) || jumlah < 1) {
            alert('Jumlah barang minimal 1.');
            return;
        }

        const barangAda = keranjang.find(item => item.id === id);

        const jumlahSekarang = barangAda ? barangAda.jumlah : 0;

        if (jumlahSekarang + jumlah > stok) {
            alert('Jumlah melebihi stok tersedia: ' + stok);
            return;
        }

        if (barangAda) {

            barangAda.jumlah += jumlah;

        } else {

            keranjang.push({
                id: id,
                nama: nama,
                harga: Number(harga),
                jumlah: jumlah,
                stok: Number(stok)
            });

        }

        inputJumlah.value = 1;

        tampilkanKeranjang();

    }


    // MENAMPILKAN KERANJANG

    function tampilkanKeranjang() {

        const tbody = document.getElementById('keranjang');

        tbody.innerHTML = '';

        let subtotalTotal = 0;

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

            subtotalTotal += subtotal;

            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>

                    <td>${escapeHTML(item.nama)}</td>

                    <td>${formatRupiah(item.harga)}</td>

                    <td>
                        <input
                            type="number"
                            min="1"
                            max="${item.stok}"
                            value="${item.jumlah}"
                            class="form-control"
                            style="width:90px"
                            onchange="ubahJumlah(${item.id}, this.value)">
                    </td>

                    <td class="harga">
                        ${formatRupiah(subtotal)}
                    </td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            onclick="hapusBarang(${item.id})">

                            Hapus

                        </button>
                    </td>
                </tr>
            `;

        });


        // HITUNG PPN DAN TOTAL

        const ppn = Math.round(subtotalTotal * 11 / 100);

        const total = subtotalTotal + ppn;

        document.getElementById('subtotalBelanja').textContent =
            formatRupiah(subtotalTotal);

        document.getElementById('ppnBelanja').textContent =
            formatRupiah(ppn);

        document.getElementById('totalBelanja').textContent =
            formatRupiah(total);

        siapkanDataForm();

    }


    // MENCEGAH HTML INJECTION

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


    // MENGUBAH JUMLAH

    function ubahJumlah(id, jumlah) {

        jumlah = parseInt(jumlah);

        const barang = keranjang.find(item => item.id === id);

        if (!barang) return;

        if (
            !Number.isInteger(jumlah) ||
            jumlah < 1 ||
            jumlah > barang.stok
        ) {

            alert('Jumlah tidak valid atau melebihi stok.');

            tampilkanKeranjang();

            return;

        }

        barang.jumlah = jumlah;

        tampilkanKeranjang();

    }


    // HAPUS BARANG

    function hapusBarang(id) {

        keranjang = keranjang.filter(item => item.id !== id);

        tampilkanKeranjang();

    }


    // KOSONGKAN KERANJANG

    function kosongkanKeranjang() {

        keranjang = [];

        tampilkanKeranjang();

    }


    // MENYIAPKAN INPUT HIDDEN

    function siapkanDataForm() {

        const form = document.getElementById('formPenjualan');

        form.querySelectorAll('.input-keranjang').forEach(input => {
            input.remove();
        });

        keranjang.forEach(item => {

            const inputBarang = document.createElement('input');

            inputBarang.type = 'hidden';
            inputBarang.name = 'idbarang[]';
            inputBarang.value = item.id;
            inputBarang.className = 'input-keranjang';

            const inputJumlah = document.createElement('input');

            inputJumlah.type = 'hidden';
            inputJumlah.name = 'jumlah[]';
            inputJumlah.value = item.jumlah;
            inputJumlah.className = 'input-keranjang';

            form.appendChild(inputBarang);
            form.appendChild(inputJumlah);

        });

    }


    // VALIDASI SEBELUM SUBMIT

    document.getElementById('formPenjualan')
        .addEventListener('submit', function(event) {

            if (keranjang.length === 0) {

                event.preventDefault();

                alert('Keranjang masih kosong.');

                return;

            }

            siapkanDataForm();

            document.getElementById('btnSimpan').disabled = true;

        });

</script>

</body>
</html>

@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Tambah Pengadaan</h3>
            <p class="text-muted">Form pencatatan pembelian barang dari vendor.</p>
        </div>

        <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengadaan.store') }}" method="POST">
        @csrf

        <!-- PILIH VENDOR -->
        <div class="card mb-4">
            <div class="card-header">
                <strong>Informasi Pengadaan</strong>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Vendor</label>

                    <select name="idvendor" class="form-select" required>
                        <option value="">-- Pilih Vendor --</option>

                        @foreach ($vendor as $v)
                            <option value="{{ $v->idvendor }}">
                                {{ $v->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- DETAIL BARANG -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <strong>Detail Barang</strong>

                <button type="button" class="btn btn-sm btn-primary"
                    onclick="tambahBarang()">
                    + Tambah Barang
                </button>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Barang</th>
                                <th width="20%">Harga Satuan</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">Subtotal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="daftarBarang">
                            <tr>
                                <td>
                                    <select name="idbarang[]" class="form-select barang-select"
                                        onchange="hitungBaris(this)" required>
                                        <option value="">-- Pilih Barang --</option>

                                        @foreach ($barang as $b)
                                            <option value="{{ $b->idbarang }}"
                                                data-harga="{{ $b->harga }}">
                                                {{ $b->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="text" class="form-control harga"
                                        value="0" readonly>
                                </td>

                                <td>
                                    <input type="number" name="jumlah[]"
                                        class="form-control jumlah"
                                        min="1" value="1"
                                        oninput="hitungBaris(this)" required>
                                </td>

                                <td>
                                    <input type="text" class="form-control subtotal"
                                        value="0" readonly>
                                </td>

                                <td>
                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="hapusBaris(this)">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- RINGKASAN -->
        <div class="card mb-4">
            <div class="card-header">
                <strong>Ringkasan Pembayaran</strong>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <label class="col-md-8 col-form-label">Subtotal</label>
                    <div class="col-md-4">
                        <input type="text" id="subtotalTotal"
                            class="form-control" value="Rp 0" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-8 col-form-label">PPN (11%)</label>
                    <div class="col-md-4">
                        <input type="text" id="ppnTotal"
                            class="form-control" value="Rp 0" readonly>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-8 col-form-label">
                        <strong>Total Pengadaan</strong>
                    </label>
                    <div class="col-md-4">
                        <input type="text" id="totalPengadaan"
                            class="form-control fw-bold" value="Rp 0" readonly>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pengadaan.index') }}"
                class="btn btn-secondary">
                Batal
            </a>

            <button type="submit" class="btn btn-success">
                Simpan Pengadaan
            </button>
        </div>

    </form>
</div>

<script>
    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function hitungBaris(element) {
        const baris = element.closest('tr');

        const select = baris.querySelector('.barang-select');
        const jumlahInput = baris.querySelector('.jumlah');

        const hargaInput = baris.querySelector('.harga');
        const subtotalInput = baris.querySelector('.subtotal');

        const harga = Number(
            select.options[select.selectedIndex]?.dataset.harga || 0
        );

        const jumlah = Math.max(0, Number(jumlahInput.value) || 0);
        const subtotal = harga * jumlah;

        hargaInput.value = formatRupiah(harga);
        subtotalInput.value = formatRupiah(subtotal);

        hitungTotal();
    }

    function tambahBarang() {
        const tbody = document.getElementById('daftarBarang');
        const barisPertama = tbody.querySelector('tr');
        const barisBaru = barisPertama.cloneNode(true);

        barisBaru.querySelector('.barang-select').value = '';
        barisBaru.querySelector('.jumlah').value = 1;
        barisBaru.querySelector('.harga').value = 'Rp 0';
        barisBaru.querySelector('.subtotal').value = 'Rp 0';

        tbody.appendChild(barisBaru);

        hitungTotal();
    }

    function hapusBaris(button) {
        const tbody = document.getElementById('daftarBarang');

        if (tbody.querySelectorAll('tr').length === 1) {
            alert('Minimal harus ada satu barang.');
            return;
        }

        button.closest('tr').remove();

        hitungTotal();
    }

    function hitungTotal() {
        let subtotalTotal = 0;

        document.querySelectorAll('#daftarBarang tr').forEach(baris => {
            const select = baris.querySelector('.barang-select');
            const jumlah = Number(baris.querySelector('.jumlah').value) || 0;

            const harga = Number(
                select.options[select.selectedIndex]?.dataset.harga || 0
            );

            subtotalTotal += harga * jumlah;
        });

        const ppn = Math.round(subtotalTotal * 11 / 100);
        const total = subtotalTotal + ppn;

        document.getElementById('subtotalTotal').value =
            formatRupiah(subtotalTotal);

        document.getElementById('ppnTotal').value =
            formatRupiah(ppn);

        document.getElementById('totalPengadaan').value =
            formatRupiah(total);
    }
</script>
@endsection

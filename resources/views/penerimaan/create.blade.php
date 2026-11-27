@extends('layouts.app')

@section('title', 'Tambah Penerimaan - Toko Kopi')

@section('css')
<style>
    body {
        background-color: #f5f3ef;
    }

    .judul {
        color: #4b2e20;
        font-weight: bold;
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

    .card-header {
        background-color: #5d4037;
        color: white;
        font-weight: bold;
    }

    .card {
        border: none;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')

<div class="container mt-3">

    {{-- JUDUL DAN TOMBOL KEMBALI --}}

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h3 class="judul mb-2">
                Tambah Penerimaan Barang
            </h3>

            <p class="text-muted mb-0">
                Catat jumlah barang yang benar-benar diterima.
            </p>
        </div>

        <a href="{{ route('penerimaan.index') }}"
           class="btn btn-dashboard btn-sm px-3 py-2">

            ← Kembali

        </a>

    </div>


    {{-- NOTIFIKASI ERROR --}}

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- FORM PENERIMAAN --}}

    <form action="{{ route('penerimaan.store') }}" method="POST">

        @csrf

        {{-- INFORMASI PENGADAAN --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                Informasi Pengadaan
            </div>

            <div class="card-body">

                <label class="form-label fw-bold">
                    Pilih Pengadaan
                </label>

                <select name="idpengadaan"
                        id="idpengadaan"
                        class="form-select"
                        required>

                    <option value="">
                        -- Pilih Pengadaan --
                    </option>

                    @foreach($pengadaan as $p)

                        <option value="{{ $p->idpengadaan }}"
                            {{ old('idpengadaan') == $p->idpengadaan ? 'selected' : '' }}>

                            Pengadaan #{{ $p->idpengadaan }}
                            - {{ $p->nama_vendor }}

                        </option>

                    @endforeach

                </select>

                <small class="text-muted">
                    Pilih transaksi pengadaan yang akan diterima barangnya.
                </small>

            </div>

        </div>


        {{-- DETAIL BARANG --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                Detail Barang yang Diterima
            </div>

            <div class="card-body">

                <div id="areaBarang">

                    <p class="text-muted mb-0">
                        Silakan pilih pengadaan terlebih dahulu.
                    </p>

                </div>

            </div>

        </div>


        {{-- TOMBOL SIMPAN --}}

        <div class="d-flex justify-content-end mb-4">

            <button type="submit"
                    class="btn btn-success px-4">

                Simpan Penerimaan

            </button>

        </div>

    </form>

</div>


{{-- JAVASCRIPT --}}

<script>

document.getElementById('idpengadaan')
.addEventListener('change', function () {

    const id = this.value;

    const area = document.getElementById('areaBarang');

    if (!id) {

        area.innerHTML =
            '<p class="text-muted">Pilih pengadaan terlebih dahulu.</p>';

        return;
    }

    area.innerHTML =
        '<p class="text-muted">Mengambil data barang...</p>';

    fetch("{{ url('/penerimaan/detail-pengadaan') }}/" + id)

        .then(response => {

            if (!response.ok) {
                throw new Error('Gagal mengambil data barang.');
            }

            return response.json();

        })

        .then(data => {

            if (data.length === 0) {

                area.innerHTML =
                    '<div class="alert alert-warning">Detail pengadaan tidak ditemukan.</div>';

                return;
            }

            let html = `
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Pesanan</th>
                                <th>Sudah Diterima</th>
                                <th>Sisa Pesanan</th>
                                <th>Jumlah Diterima Sekarang</th>
                            </tr>

                        </thead>

                        <tbody>
            `;

            data.forEach(item => {

                const sisa =
                    Number(item.jumlah) -
                    Number(item.sudah_diterima);

                html += `
                    <tr>

                        <td>
                            ${item.nama}

                            <input type="hidden"
                                   name="idbarang[]"
                                   value="${item.idbarang}">
                        </td>

                        <td>
                            ${item.jumlah} ${item.nama_satuan}
                        </td>

                        <td>
                            ${item.sudah_diterima}
                        </td>

                        <td>
                            <strong>${sisa}</strong>
                        </td>

                        <td>

                            <input type="number"
                                   name="jumlah_terima[]"
                                   class="form-control"
                                   min="0"
                                   max="${sisa}"
                                   value="0"
                                   required>

                        </td>

                    </tr>
                `;

            });

            html += `
                        </tbody>

                    </table>

                </div>
            `;

            area.innerHTML = html;

        })

        .catch(error => {

            area.innerHTML = `
                <div class="alert alert-danger">
                    ${error.message}
                </div>
            `;

        });

});

</script>

@endsection

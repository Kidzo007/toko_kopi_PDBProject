@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="mb-4">
        <h3 style="color:#3e2723;">Kartu Stok Barang</h3>
        <p class="text-muted">
            Riwayat barang masuk dan keluar dari sistem persediaan.
        </p>
    </div>

    <!-- RINGKASAN -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Jenis Barang Tercatat</h6>
                    <h3>{{ $jumlahBarang->total }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang Masuk</h6>
                    <h3 class="text-success">
                        {{ number_format($totalMasuk->total) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang Keluar</h6>
                    <h3 class="text-danger">
                        {{ number_format($totalKeluar->total) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    <!-- TOMBOL -->
    <div class="d-flex justify-content-between mb-3">

        <h5>Riwayat Pergerakan Stok</h5>

        <a href="{{ route('kartu_stok.stok') }}"
           class="btn btn-primary">
            Lihat Stok Barang
        </a>

    </div>


    <!-- PENCARIAN -->
    <div class="card mb-4">

        <div class="card-body">

            <form action="{{ route('kartu_stok.index') }}"
                  method="GET">

                <div class="input-group">

                    <input type="text"
                           name="keyword"
                           class="form-control"
                           placeholder="Cari nama barang atau ID transaksi..."
                           value="{{ $keyword }}">

                    <button class="btn btn-primary">
                        Cari
                    </button>

                    <a href="{{ route('kartu_stok.index') }}"
                       class="btn btn-secondary">
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    <!-- TABEL KARTU STOK -->
    <div class="card">

        <div class="card-header">
            <strong>Data Kartu Stok</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jenis Transaksi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok Akhir</th>
                            <th>ID Transaksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($kartuStok as $index => $ks)

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>{{ $ks->created_at }}</td>

                            <td>
                                {{ $ks->nama_barang }}
                            </td>

                            <td>
                                @if($ks->jenis_transaksi == 'P')
                                    <span class="badge bg-success">
                                        Penerimaan
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ $ks->jenis_transaksi }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-success">
                                {{ number_format($ks->masuk) }}
                                {{ $ks->nama_satuan }}
                            </td>

                            <td class="text-danger">
                                {{ number_format($ks->keluar) }}
                                {{ $ks->nama_satuan }}
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($ks->stock) }}
                                    {{ $ks->nama_satuan }}
                                </strong>
                            </td>

                            <td>{{ $ks->idtransaksi }}</td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center">
                                Belum ada riwayat kartu stok.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

@extends('layouts.app')

@section('title', 'Data Penerimaan - Toko Kopi')

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

    .btn-primary {
        background-color: #4b2e20;
        border-color: #4b2e20;
    }

    .btn-primary:hover {
        background-color: #65412f;
        border-color: #65412f;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .card-header {
        background-color: #5d4037;
        color: white;
        font-weight: bold;
    }
</style>
@endsection

@section('content')

<div class="container mt-3">

    {{-- JUDUL DAN TOMBOL --}}

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h3 class="judul mb-2">
                Data Penerimaan Barang
            </h3>

            <p class="text-muted mb-0">
                Daftar barang yang sudah diterima dari vendor.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            {{-- TOMBOL KEMBALI SESUAI ROLE --}}

            @if(session('nama_role') === 'Administrator')
                <a href="{{ route('dashboard.administrator') }}"
                   class="btn btn-dashboard btn-sm px-3 py-2">
                    ← Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('dashboard.gudang') }}"
                   class="btn btn-dashboard btn-sm px-3 py-2">
                    ← Kembali ke Dashboard
                </a>
            @endif

            <a href="{{ route('penerimaan.create') }}"
               class="btn btn-primary btn-sm px-3 py-2">
                + Tambah Penerimaan
            </a>

        </div>

    </div>


    {{-- NOTIFIKASI BERHASIL --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- NOTIFIKASI ERROR --}}

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- TABEL PENERIMAAN --}}

    <div class="card shadow-sm">

        <div class="card-header py-3">
            <strong>Daftar Penerimaan</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>ID Penerimaan</th>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Petugas Gudang</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($penerimaan as $index => $p)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $p->idpenerimaan }}
                            </td>

                            <td>
                                {{ $p->idpengadaan }}
                            </td>

                            <td>
                                {{ $p->created_at }}
                            </td>

                            <td>
                                {{ $p->nama_vendor }}
                            </td>

                            <td>
                                {{ $p->username }}
                            </td>

                            {{-- STATUS PENERIMAAN --}}

                            <td>

                                @if($p->status == 'S')

                                    <span class="badge bg-success">
                                        Selesai
                                    </span>

                                @elseif($p->status == 'P')

                                    <span class="badge bg-warning text-dark">
                                        Proses
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $p->status }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data penerimaan.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="text-muted small">
                Total penerimaan: {{ count($penerimaan) }} transaksi
            </div>

        </div>

    </div>

</div>

@endsection

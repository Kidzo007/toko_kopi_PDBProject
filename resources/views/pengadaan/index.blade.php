@extends('layouts.app')

@section('title', 'Data Pengadaan - Toko Kopi')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1">Data Pengadaan</h3>
            <p class="text-muted mb-0">
                Mengelola data pembelian barang dari vendor.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            {{-- TOMBOL KEMBALI SESUAI ROLE --}}
            @if(session('nama_role') === 'Administrator')
                <a href="{{ route('dashboard.administrator') }}"
                   class="btn btn-secondary">
                    ← Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('dashboard.pembelian') }}"
                   class="btn btn-secondary">
                    ← Kembali ke Dashboard
                </a>
            @endif

            <a href="{{ route('pengadaan.create') }}"
               class="btn btn-primary">
                + Tambah Pengadaan
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TABEL PENGADAAN -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Daftar Pengadaan Barang</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>Petugas</th>
                            <th>Subtotal</th>
                            <th>PPN</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengadaan as $index => $p)
                            <tr>
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $p->idpengadaan }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($p->timestamp)->format('d-m-Y H:i') }}
                                </td>

                                <td>
                                    {{ $p->nama_vendor }}
                                </td>

                                <td>
                                    {{ $p->username }}
                                </td>

                                <td>
                                    Rp {{ number_format($p->subtotal_nilai, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($p->ppn, 0, ',', '.') }}
                                </td>

                                <td class="fw-bold">
                                    Rp {{ number_format($p->total_nilai, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    @if($p->status == 'S')
                                        <span class="badge bg-success">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            {{ $p->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <h6 class="text-muted">
                                        Belum ada data pengadaan.
                                    </h6>

                                    <p class="text-muted mb-3">
                                        Silakan tambahkan pengadaan baru.
                                    </p>

                                    <a href="{{ route('pengadaan.create') }}"
                                       class="btn btn-primary btn-sm">
                                        + Tambah Pengadaan
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="text-muted small">
                Total data pengadaan: {{ count($pengadaan) }} transaksi
            </div>

        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- JUDUL HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Pengadaan</h3>
            <p class="text-muted mb-0">
                Data transaksi pengadaan menggunakan Stored Procedure
            </p>
        </div>

        <a href="{{ route('pengadaan.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>


    {{-- TABEL DETAIL PENGADAAN --}}
    <div class="card shadow-sm border-0">

        <div class="card-header text-white"
             style="background-color: #5d4037;">

            <h5 class="mb-0">
                Data Detail Pengadaan
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>
                            <th>No</th>
                            <th>ID Pengadaan</th>
                            <th>Tanggal</th>
                            <th>Vendor</th>
                            <th>User</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal Barang</th>
                            <th>Subtotal Pengadaan</th>
                            <th>PPN</th>
                            <th>Total Pengadaan</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($data as $index => $item)

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td class="text-center">
                                    {{ $item->idpengadaan }}
                                </td>

                                <td>
                                    {{ $item->tanggal_pengadaan }}
                                </td>

                                <td>
                                    {{ $item->nama_vendor }}
                                </td>

                                <td>
                                    {{ $item->nama_user }}
                                </td>

                                <td>
                                    {{ $item->nama_barang }}
                                </td>

                                <td class="text-center">
                                    {{ $item->nama_satuan }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    {{ $item->jumlah }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($item->sub_total, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($item->ppn, 0, ',', '.') }}
                                </td>

                                <td class="text-end fw-bold">
                                    Rp {{ number_format($item->total_nilai, 0, ',', '.') }}
                                </td>

                                <td class="text-center">

                                    @if ($item->status == 'S')
                                        <span class="badge bg-success">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            {{ $item->status }}
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="14"
                                    class="text-center text-muted py-4">

                                    Belum ada data pengadaan.

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

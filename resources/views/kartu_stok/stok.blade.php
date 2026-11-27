@extends('layouts.app')

@section('content')

@php
    $role = session('nama_role');

    $dashboard = [
        'Administrator' => '/dashboard/administrator',
        'Gudang' => '/dashboard/gudang',
        'Manager' => '/dashboard/manager',
        'Kasir' => '/dashboard/kasir',
        'Staff Pembelian' => '/dashboard/pembelian',
    ];

    $dashboardUrl = $dashboard[$role] ?? '/';
@endphp

<div class="container mt-4">

    {{-- JUDUL DAN TOMBOL KEMBALI --}}

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h3 style="color:#3e2723;">
                Stok Barang
            </h3>

            <p class="text-muted mb-0">
                Informasi jumlah stok terakhir setiap barang.
            </p>
        </div>

        <a href="{{ url($dashboardUrl) }}"
           class="btn btn-secondary"
           style="height:40px;">

            ← Kembali ke Dashboard

        </a>

    </div>


    {{-- TABEL DAFTAR STOK BARANG --}}

    <div class="card">

        <div class="card-header">
            <strong>Daftar Stok Barang</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok Terakhir</th>
                            <th>Status Stok</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($stokBarang as $index => $b)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $b->idbarang }}
                                </td>

                                <td>
                                    {{ $b->nama }}
                                </td>

                                <td>
                                    {{ $b->nama_satuan }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($b->stok_terakhir) }}
                                    </strong>
                                </td>

                                {{-- STATUS STOK --}}

                                <td>

                                    @if($b->stok_terakhir > 0)

                                        <span class="badge bg-success">
                                            Stok Tersedia
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Stok Habis
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada barang.
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

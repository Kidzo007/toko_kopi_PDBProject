@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-4">

        <div>
            <h3 style="color:#3e2723;">Stok Barang</h3>

            <p class="text-muted">
                Informasi jumlah stok terakhir setiap barang.
            </p>
        </div>

        <a href="{{ route('kartu_stok.index') }}"
           class="btn btn-secondary"
           style="height:40px;">
            Kembali
        </a>

    </div>


    <div class="card">

        <div class="card-header">
            <strong>Daftar Stok Barang</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

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

                            <td>{{ $index + 1 }}</td>

                            <td>{{ $b->idbarang }}</td>

                            <td>{{ $b->nama }}</td>

                            <td>{{ $b->nama_satuan }}</td>

                            <td>
                                <strong>
                                    {{ number_format($b->stok_terakhir) }}
                                </strong>
                            </td>

                            <td>

                                @if($b->stok_terakhir <= 0)

                                    <span class="badge bg-danger">
                                        Stok Habis
                                    </span>

                                @elseif($b->stok_terakhir <= 5)

                                    <span class="badge bg-warning text-dark">
                                        Stok Menipis
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Stok Aman
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

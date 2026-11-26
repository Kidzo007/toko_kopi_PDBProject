@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-4">

        <div>
            <h3>Data Penerimaan Barang</h3>
            <p class="text-muted">
                Daftar barang yang sudah diterima dari vendor.
            </p>
        </div>

        <a href="{{ route('penerimaan.create') }}"
           class="btn btn-primary">
            + Tambah Penerimaan
        </a>

    </div>

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

    <div class="card">

        <div class="card-header">
            <strong>Daftar Penerimaan</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-light">
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
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->idpenerimaan }}</td>
                            <td>{{ $p->idpengadaan }}</td>
                            <td>{{ $p->created_at }}</td>
                            <td>{{ $p->nama_vendor }}</td>
                            <td>{{ $p->username }}</td>
                            <td>{{ $p->status }}</td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada data penerimaan.
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

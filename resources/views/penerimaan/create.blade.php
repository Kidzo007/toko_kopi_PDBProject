@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-4">

        <div>
            <h3>Tambah Penerimaan Barang</h3>
            <p class="text-muted">
                Catat jumlah barang yang benar-benar diterima.
            </p>
        </div>

        <a href="{{ route('penerimaan.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </div>

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

    <form action="{{ route('penerimaan.store') }}" method="POST">

        @csrf

        <div class="card mb-4">

            <div class="card-header">
                Informasi Pengadaan
            </div>

            <div class="card-body">

                <label class="form-label">
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

                        <option value="{{ $p->idpengadaan }}">
                            Pengadaan #{{ $p->idpengadaan }}
                            - {{ $p->nama_vendor }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        <div class="card mb-4">

            <div class="card-header">
                Detail Barang yang Diterima
            </div>

            <div class="card-body">

                <div id="areaBarang">

                    <p class="text-muted">
                        Silakan pilih pengadaan terlebih dahulu.
                    </p>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <button type="submit"
                    class="btn btn-success">
                Simpan Penerimaan
            </button>

        </div>

    </form>

</div>


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
                <table class="table table-bordered align-middle">

                    <thead class="table-light">
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

                        <td>${item.jumlah} ${item.nama_satuan}</td>

                        <td>${item.sudah_diterima}</td>

                        <td>${sisa}</td>

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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistem Toko Kopi')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #3e2723;
            padding: 12px 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .card-header {
            background-color: #5d4037;
            color: white;
        }

        .btn-primary {
            background-color: #5d4037;
            border-color: #5d4037;
        }

        .btn-primary:hover {
            background-color: #3e2723;
            border-color: #3e2723;
        }
    </style>

    @yield('css')
</head>

<body>

    {{-- NAVBAR --}}

    <nav class="navbar navbar-expand-lg navbar-dark">

        <div class="container-fluid px-4">

            <a class="navbar-brand" href="#">
                ☕ TOKO KOPI
            </a>

            <div class="ms-auto text-white">
                {{ session('username') }}
            </div>

        </div>

    </nav>

    {{-- KONTEN HALAMAN --}}

    <div class="container py-4">

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

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @yield('js')

</body>
</html>

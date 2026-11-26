<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kopi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #3e2723, #795548);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 18px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .coffee-icon {
            font-size: 55px;
        }

        .btn-coffee {
            background-color: #5d4037;
            color: white;
        }

        .btn-coffee:hover {
            background-color: #3e2723;
            color: white;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="text-center mb-4">
        <div class="coffee-icon">☕</div>
        <h3 class="fw-bold">TOKO KOPI</h3>
        <p class="text-muted">Silakan login untuk melanjutkan</p>
    </div>

    {{-- Pesan error --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Pesan berhasil --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validasi --}}
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Masukkan username"
                value="{{ old('username') }}"
                required
                autofocus
            >
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Masukkan password"
                required
            >
        </div>

        <button type="submit" class="btn btn-coffee w-100 py-2">
            LOGIN
        </button>
    </form>

    <div class="text-center mt-4">
        <small class="text-muted">
            © 2026 Sistem Informasi Penjualan Kopi
        </small>
    </div>

</div>

</body>
</html>

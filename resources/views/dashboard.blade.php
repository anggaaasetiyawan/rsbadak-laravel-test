<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f5f5f5;
            margin: 0;
        }
        nav {
            background: #111;
            color: #fff;
            padding: .75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav span { font-size: .875rem; }
        nav form { display: inline; }
        nav button {
            background: none;
            border: 1px solid #555;
            color: #fff;
            padding: .3rem .75rem;
            font-size: .8rem;
            cursor: pointer;
        }
        nav button:hover { border-color: #fff; }
        .content {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        h1 { font-size: 1.25rem; margin-bottom: .5rem; }
        p { color: #555; font-size: .875rem; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="/admin/dashboard"style="color:#fff;text-decoration:none;font-size:.875rem;margin-right:1rem">Dashboard</a>
            <a href="{{ route('pasien.index') }}" style="color:#aaa;text-decoration:none;font-size:.875rem;margin-right:1rem">Pasien</a>
            <a href="{{ route('dokters.index') }}" style="color:#aaa;text-decoration:none;font-size:.875rem;margin-right:1rem">Dokter</a>
            <a href="{{ route('polikliniks.index') }}" style="color:#aaa;text-decoration:none;font-size:.875rem;margin-right:1rem">Poliklinik</a>
            <a href="{{ route('jadwal-dokter.index') }}" style="color:#aaa;text-decoration:none;font-size:.875rem;margin-right:1rem">Jadwal</a>
            <a href="{{ route('registrasi.index') }}" style="color:#aaa;text-decoration:none;font-size:.875rem;margin-right:1rem">Registrasi</a>
        </div>
        <div style="display:flex;align-items:center;gap:1rem">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>
    <div class="content">
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ Auth::user()->name }}.</p>
    </div>
</body>
</html>

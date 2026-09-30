<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; margin: 0; }
        nav { background: #111; color: #fff; padding: .75rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #aaa; text-decoration: none; font-size: .875rem; margin-right: 1rem; }
        nav a:hover { color: #fff; }
        nav .right { display: flex; align-items: center; gap: 1rem; }
        nav .right span { font-size: .8rem; color: #aaa; }
        nav .right form { display: inline; }
        nav .right button { background: none; border: 1px solid #555; color: #fff; padding: .3rem .75rem; font-size: .8rem; cursor: pointer; }
        nav .right button:hover { border-color: #fff; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        h1 { font-size: 1.25rem; margin: 0; }
        .btn { display: inline-block; padding: .5rem 1rem; font-size: .875rem; border: none; cursor: pointer; text-decoration: none; }
        .btn-primary { background: #111; color: #fff; }
        .btn-primary:hover { background: #333; }
        .btn-sm { padding: .25rem .5rem; font-size: .75rem; }
        .btn-secondary { background: #e5e5e5; color: #111; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: .5rem .75rem; text-align: left; border-bottom: 1px solid #e5e5e5; font-size: .875rem; }
        th { background: #fafafa; font-weight: 600; }
        .search { display: flex; gap: .5rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .search input, .search select { padding: .4rem .6rem; border: 1px solid #ccc; font-size: .875rem; }
        .search button { padding: .4rem .75rem; background: #111; color: #fff; border: none; font-size: .875rem; cursor: pointer; }
        .badge { display: inline-block; padding: .1rem .4rem; font-size: .7rem; border-radius: 3px; }
        .badge-active { background: #dbeafe; color: #1e40af; }
        .badge-done { background: #dcfce7; color: #166534; }
        .badge-cancel { background: #fee2e2; color: #991b1b; }
        .pagination { margin-top: 1rem; }
        .alert { padding: .5rem .75rem; margin-bottom: 1rem; font-size: .875rem; border: 1px solid; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #166534; }
        .alert-error { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="/admin/dashboard">Dashboard</a>
            <a href="{{ route('pasien.index') }}">Pasien</a>
            <a href="{{ route('dokters.index') }}">Dokter</a>
            <a href="{{ route('polikliniks.index') }}">Poliklinik</a>
            <a href="{{ route('jadwal-dokter.index') }}">Jadwal</a>
            <a href="{{ route('registrasi.index') }}" style="color:#fff">Registrasi</a>
        </div>
        <div class="right">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

        <div class="header">
            <h1>Registrasi Pasien</h1>
            <a href="{{ route('registrasi.create') }}" class="btn btn-primary">+ Registrasi Baru</a>
        </div>

        <form class="search" method="GET">
            <input type="text" name="search" placeholder="Cari nama pasien / no. registrasi..." value="{{ request('search') }}">
            <select name="polyclinic_id">
                <option value="">Semua Poli</option>
                @foreach($polyclinics as $p)
                    <option value="{{ $p->id }}" {{ request('polyclinic_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">Semua Status</option>
                <option value="terdaftar" {{ request('status') === 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <input type="date" name="visit_date" value="{{ request('visit_date') }}">
            <button type="submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>No. Registrasi</th>
                    <th>Pasien</th>
                    <th>Poliklinik</th>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Antrian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrasi as $r)
                <tr>
                    <td>{{ $r->registration_no }}</td>
                    <td>{{ $r->patient->name ?? '-' }}</td>
                    <td>{{ $r->polyclinic->name ?? '-' }}</td>
                    <td>{{ $r->doctor->name ?? '-' }}</td>
                    <td>{{ $r->visit_date->format('d/m/Y') }}</td>
                    <td>{{ $r->queue_no }}</td>
                    <td>
                        @if($r->status === 'terdaftar')
                            <span class="badge badge-active">Terdaftar</span>
                        @elseif($r->status === 'selesai')
                            <span class="badge badge-done">Selesai</span>
                        @else
                            <span class="badge badge-cancel">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('registrasi.show', $r) }}" class="btn btn-sm btn-secondary">Detail</a>
                        <a href="{{ route('registrasi.edit', $r) }}" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:#888;padding:2rem">Belum ada data registrasi.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $registrasi->links() }}</div>
    </div>
</body>
</html>

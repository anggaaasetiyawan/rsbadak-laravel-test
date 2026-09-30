<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dokter</title>
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
        .container { max-width: 960px; margin: 2rem auto; padding: 0 1rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        h1 { font-size: 1.25rem; margin: 0; }
        .btn { display: inline-block; padding: .5rem 1rem; font-size: .875rem; border: none; cursor: pointer; text-decoration: none; }
        .btn-primary { background: #111; color: #fff; }
        .btn-primary:hover { background: #333; }
        .btn-sm { padding: .25rem .5rem; font-size: .75rem; }
        .btn-danger { background: #991b1b; color: #fff; }
        .btn-danger:hover { background: #7f1d1d; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: .5rem .75rem; text-align: left; border-bottom: 1px solid #e5e5e5; font-size: .875rem; }
        th { background: #fafafa; font-weight: 600; }
        .search { display: flex; gap: .5rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .search input, .search select { padding: .4rem .6rem; border: 1px solid #ccc; font-size: .875rem; }
        .search button { padding: .4rem .75rem; background: #111; color: #fff; border: none; font-size: .875rem; cursor: pointer; }
        .badge { display: inline-block; padding: .1rem .4rem; font-size: .7rem; border-radius: 3px; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .pagination { margin-top: 1rem; }
        .alert { padding: .5rem .75rem; margin-bottom: 1rem; font-size: .875rem; border: 1px solid; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #166534; }
        .alert-error { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }
    </style>
</head>
<body>
    @include('partials.nav')
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

        <div class="header">
            <h1>Jadwal Dokter</h1>
            <a href="{{ role_route('jadwal-dokter.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
        </div>

        <form class="search" method="GET">
            <input type="text" name="search" placeholder="Cari nama dokter..." value="{{ request('search') }}">
            <select name="polyclinic_id">
                <option value="">Semua Poli</option>
                @foreach($polyclinics as $p)
                    <option value="{{ $p->id }}" {{ request('polyclinic_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="day_of_week">
                <option value="">Semua Hari</option>
                @foreach($days as $d)
                    <option value="{{ $d }}" {{ request('day_of_week') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            <button type="submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Poliklinik</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Maks Pasien</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                <tr>
                    <td>{{ $s->doctor->name }}</td>
                    <td>{{ $s->doctor->polyclinic->name ?? '-' }}</td>
                    <td>{{ $s->day_of_week }}</td>
                    <td>{{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }}</td>
                    <td>{{ $s->max_patients }}</td>
                    <td><span class="badge {{ $s->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <a href="{{ role_route('jadwal-dokter.edit', $s) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" action="{{ role_route('jadwal-dokter.destroy', $s) }}" style="display:inline" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:#888;padding:2rem">Belum ada jadwal dokter.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $schedules->links() }}</div>
    </div>
</body>
</html>

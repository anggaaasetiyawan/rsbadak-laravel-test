<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik</title>
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
        .btn-sm { padding: .25rem .5rem; font-size: .8rem; }
        .btn-danger { background: #991b1b; color: #fff; }
        .btn-danger:hover { background: #7f1d1d; }
        .search-bar { display: flex; gap: .5rem; margin-bottom: 1rem; }
        .search-bar input { padding: .4rem .6rem; border: 1px solid #ccc; font-size: .875rem; flex: 1; max-width: 300px; }
        .search-bar button { padding: .4rem .75rem; background: #111; color: #fff; border: 0; font-size: .875rem; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #ddd; }
        th, td { text-align: left; padding: .6rem .75rem; font-size: .875rem; border-bottom: 1px solid #eee; }
        th { background: #fafafa; font-weight: 600; }
        .badge { display: inline-block; padding: .15rem .5rem; font-size: .75rem; border-radius: 2px; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: .25rem; }
        .alert { padding: .5rem .75rem; font-size: .875rem; margin-bottom: 1rem; border: 1px solid; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #166534; }
        .pagination { margin-top: 1rem; font-size: .875rem; }
        .pagination a { color: #111; }
    </style>
</head>
<body>
    @include('partials.nav')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="header">
            <h1>Data Poliklinik</h1>
            <a href="{{ role_route('polikliniks.create') }}" class="btn btn-primary">+ Tambah Poliklinik</a>
        </div>

        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Cari nama poliklinik" value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Poliklinik</th>
                    <th>Jumlah dokter</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($polyclinics as $p)
                <tr>
                    <td>{{ ($polyclinics->currentPage() - 1) * $polyclinics->perPage() + $loop->iteration }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->dokters_count ?? $p->dokters()->count() }}</td>
                    <td>
                        <span class="badge {{ $p->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="actions">
                        <a href="{{ role_route('polikliniks.edit', $p) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" action="{{ role_route('polikliniks.destroy', $p) }}" onsubmit="return confirm('Hapus poliklinik ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:#888;padding:2rem">Belum ada data poliklinik.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">{{ $polyclinics->links() }}</div>
    </div>
</body>
</html>

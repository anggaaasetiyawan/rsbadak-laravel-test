<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; margin: 0; }
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
        .search { display: flex; gap: .5rem; margin-bottom: 1rem; }
        .search input { padding: .4rem .6rem; border: 1px solid #ccc; font-size: .875rem; flex: 1; max-width: 300px; }
        .search button { padding: .4rem .75rem; background: #111; color: #fff; border: none; font-size: .875rem; cursor: pointer; }
        .badge { display: inline-block; padding: .1rem .4rem; font-size: .7rem; border-radius: 3px; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .badge-admin { background: #dbeafe; color: #1e40af; }
        .badge-petugas { background: #fef3c7; color: #92400e; }
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
            <h1>Manajemen Pengguna</h1>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
        </div>

        <form class="search" method="GET">
            <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
            <button type="submit">Cari</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge {{ $u->role === 'admin' ? 'badge-admin' : 'badge-petugas' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $u->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>{{ $u->created_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-primary">Edit</a>
                        @if($u->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#888;padding:2rem">Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $users->links() }}</div>
    </div>
</body>
</html>

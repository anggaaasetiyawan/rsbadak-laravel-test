<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Registrasi</title>
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
        .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        h1 { font-size: 1.25rem; margin-bottom: 1.5rem; }
        .card { background: #fff; padding: 1.25rem; border: 1px solid #e5e5e5; margin-bottom: 1rem; }
        .row { display: flex; margin-bottom: .5rem; }
        .label { width: 160px; font-size: .875rem; color: #555; }
        .value { font-size: .875rem; font-weight: 600; }
        .badge { display: inline-block; padding: .15rem .5rem; font-size: .75rem; border-radius: 3px; }
        .badge-active { background: #dbeafe; color: #1e40af; }
        .badge-done { background: #dcfce7; color: #166534; }
        .badge-cancel { background: #fee2e2; color: #991b1b; }
        .btn { display: inline-block; padding: .5rem 1rem; font-size: .875rem; border: none; cursor: pointer; text-decoration: none; }
        .btn-primary { background: #111; color: #fff; }
        .btn-primary:hover { background: #333; }
        .btn-secondary { background: #e5e5e5; color: #111; }
        .btn-secondary:hover { background: #d4d4d4; }
        .btn-danger { background: #991b1b; color: #fff; }
        .actions { display: flex; gap: .5rem; margin-top: 1.5rem; }
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

        <h1>Detail Registrasi</h1>

        <div class="card">
            <div class="row">
                <div class="label">No. Registrasi</div>
                <div class="value">{{ $registrasi->registration_no }}</div>
            </div>
            <div class="row">
                <div class="label">Status</div>
                <div class="value">
                    @if($registrasi->status === 'terdaftar')
                        <span class="badge badge-active">Terdaftar</span>
                    @elseif($registrasi->status === 'selesai')
                        <span class="badge badge-done">Selesai</span>
                    @else
                        <span class="badge badge-cancel">Dibatalkan</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="label">Pasien</div>
                <div class="value">{{ $registrasi->patient->name ?? '-' }} ({{ $registrasi->patient->medical_record_no ?? '-' }})</div>
            </div>
            <div class="row">
                <div class="label">Poliklinik</div>
                <div class="value">{{ $registrasi->polyclinic->name ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">Dokter</div>
                <div class="value">{{ $registrasi->doctor->name ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">Tanggal Kunjungan</div>
                <div class="value">{{ $registrasi->visit_date->format('d/m/Y') }}</div>
            </div>
            <div class="row">
                <div class="label">No. Antrian</div>
                <div class="value">{{ $registrasi->queue_no }}</div>
            </div>
            <div class="row">
                <div class="label">Keluhan</div>
                <div class="value">{{ $registrasi->complaint ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">Didaftarkan oleh</div>
                <div class="value">{{ $registrasi->registeredBy->name ?? '-' }}</div>
            </div>
            @if($registrasi->status === 'dibatalkan')
            <div class="row">
                <div class="label">Dibatalkan pada</div>
                <div class="value">{{ $registrasi->cancelled_at?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">Alasan Batal</div>
                <div class="value">{{ $registrasi->cancellation_reason ?? '-' }}</div>
            </div>
            @endif
        </div>

        <div class="actions">
            <a href="{{ route('registrasi.edit', $registrasi) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('registrasi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</body>
</html>

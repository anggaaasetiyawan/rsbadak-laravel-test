<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ ucfirst(Auth::user()->role) }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; margin: 0; color: #111; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .page-head { margin-bottom: 1.5rem; }
        .page-head h1 { font-size: 1.4rem; margin: 0 0 .25rem; }
        .page-head p { color: #666; font-size: .875rem; margin: 0; }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); margin-bottom: 1.5rem; }
        .card { background: #fff; border: 1px solid #e5e5e5; padding: 1rem 1.25rem; }
        .card .label { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #777; margin-bottom: .5rem; }
        .card .value { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .card .sub { font-size: .75rem; color: #888; margin-top: .35rem; }
        .card.accent { border-left: 3px solid #111; }
        .section { background: #fff; border: 1px solid #e5e5e5; padding: 1.25rem; margin-bottom: 1.5rem; }
        .section h2 { font-size: .95rem; margin: 0 0 1rem; font-weight: 600; }
        .chart { display: flex; align-items: flex-end; gap: .75rem; height: 180px; padding-top: .5rem; }
        .chart .bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; }
        .chart .bar { width: 100%; max-width: 46px; background: #111; min-height: 3px; }
        .chart .bar-val { font-size: .75rem; font-weight: 600; margin-bottom: .25rem; }
        .chart .bar-label { font-size: .7rem; color: #888; margin-top: .4rem; white-space: nowrap; }
        .poli-row { display: flex; align-items: center; gap: .75rem; font-size: .875rem; margin-bottom: .75rem; }
        .poli-row .name { width: 160px; flex-shrink: 0; }
        .poli-row .track { flex: 1; background: #f0f0f0; height: 10px; }
        .poli-row .fill { background: #111; height: 10px; }
        .poli-row .total { width: 32px; text-align: right; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .5rem .6rem; text-align: left; border-bottom: 1px solid #eee; font-size: .825rem; }
        th { background: #fafafa; font-weight: 600; color: #555; }
        .badge { display: inline-block; padding: .1rem .4rem; font-size: .7rem; border-radius: 3px; }
        .badge-active { background: #dbeafe; color: #1e40af; }
        .badge-done { background: #dcfce7; color: #166534; }
        .badge-cancel { background: #fee2e2; color: #991b1b; }
        .empty { text-align: center; color: #999; padding: 1.5rem; font-size: .875rem; }
        .status-row { display: flex; gap: 1rem; flex-wrap: wrap; }
        .status-pill { flex: 1; min-width: 140px; border: 1px solid #e5e5e5; padding: .85rem 1rem; }
        .status-pill .n { font-size: 1.4rem; font-weight: 700; }
        .status-pill .t { font-size: .75rem; color: #777; margin-top: .2rem; }
    </style>
</head>
<body>
    @include('partials.nav')

    <div class="container">
        <div class="page-head">
            <h1>Dashboard Statistik</h1>
            <p>Selamat datang, {{ Auth::user()->name }}. Ringkasan data aplikasi pendaftaran pasien per {{ now()->translatedFormat('d F Y') }}.</p>
        </div>

        {{-- Kartu statistik utama --}}
        <div class="grid">
            <div class="card accent">
                <div class="label">Registrasi Hari Ini</div>
                <div class="value">{{ $stats['registrasi_hari_ini'] ?? 0 }}</div>
                <div class="sub">Total: {{ $stats['total_registrasi'] ?? 0 }} registrasi</div>
            </div>
            <div class="card">
                <div class="label">Pasien</div>
                <div class="value">{{ $stats['total_pasien'] ?? 0 }}</div>
                <div class="sub">
                    @isset($stats['pasien_aktif']){{ $stats['pasien_aktif'] }} aktif @else Aktif terdaftar @endisset
                </div>
            </div>
            <div class="card">
                <div class="label">Jadwal Aktif Hari Ini</div>
                <div class="value">{{ $stats['jadwal_aktif_hari_ini'] ?? 0 }}</div>
                <div class="sub">Jadwal dokter berjalan</div>
            </div>
            @if(Auth::user()->isAdmin())
                <div class="card">
                    <div class="label">Dokter</div>
                    <div class="value">{{ $stats['total_dokter'] ?? 0 }}</div>
                    <div class="sub">{{ $stats['dokter_aktif'] ?? 0 }} aktif</div>
                </div>
                <div class="card">
                    <div class="label">Poliklinik</div>
                    <div class="value">{{ $stats['total_poliklinik'] ?? 0 }}</div>
                    <div class="sub">{{ $stats['poliklinik_aktif'] ?? 0 }} aktif</div>
                </div>
                <div class="card">
                    <div class="label">Pengguna</div>
                    <div class="value">{{ $stats['total_user'] ?? 0 }}</div>
                    <div class="sub">Admin &amp; petugas</div>
                </div>
            @endif
        </div>

        {{-- Status registrasi --}}
        <div class="section">
            <h2>Status Registrasi</h2>
            <div class="status-row">
                <div class="status-pill">
                    <div class="n">{{ $stats['registrasi_terdaftar'] ?? 0 }}</div>
                    <div class="t">Terdaftar</div>
                </div>
                <div class="status-pill">
                    <div class="n">{{ $stats['registrasi_selesai'] ?? 0 }}</div>
                    <div class="t">Selesai</div>
                </div>
                @if(Auth::user()->isAdmin())
                    <div class="status-pill">
                        <div class="n">{{ $stats['registrasi_dibatalkan'] ?? 0 }}</div>
                        <div class="t">Dibatalkan</div>
                    </div>
                @endif
            </div>
        </div>

        @if(Auth::user()->isAdmin() && isset($last7Days))
            {{-- Tren 7 hari terakhir --}}
            <div class="section">
                <h2>Registrasi 7 Hari Terakhir</h2>
                @php $maxDay = max(1, (int) $last7Days->max('total')); @endphp
                <div class="chart">
                    @foreach($last7Days as $day)
                        <div class="bar-wrap">
                            <div class="bar-val">{{ $day['total'] }}</div>
                            <div class="bar" style="height: {{ round(($day['total'] / $maxDay) * 100) }}%"></div>
                            <div class="bar-label">{{ $day['date'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Registrasi per poliklinik --}}
            <div class="section">
                <h2>Registrasi per Poliklinik</h2>
                @php $maxPoli = max(1, (int) $registrasiPerPoli->max('total')); @endphp
                @forelse($registrasiPerPoli as $poli)
                    <div class="poli-row">
                        <div class="name">{{ $poli['name'] }}</div>
                        <div class="track">
                            <div class="fill" style="width: {{ round(($poli['total'] / $maxPoli) * 100) }}%"></div>
                        </div>
                        <div class="total">{{ $poli['total'] }}</div>
                    </div>
                @empty
                    <div class="empty">Belum ada data registrasi per poliklinik.</div>
                @endforelse
            </div>
        @endif

        {{-- Registrasi terbaru --}}
        <div class="section">
            <h2>Registrasi Terbaru</h2>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRegistrations as $r)
                        <tr>
                            <td>{{ $r->registration_no }}</td>
                            <td>{{ $r->patient->name ?? '-' }}</td>
                            <td>{{ $r->polyclinic->name ?? '-' }}</td>
                            <td>{{ $r->doctor->name ?? '-' }}</td>
                            <td>{{ $r->visit_date?->format('d/m/Y') ?? '-' }}</td>
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
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Belum ada data registrasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

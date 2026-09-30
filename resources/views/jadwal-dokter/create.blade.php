<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Dokter</title>
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
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: .875rem; font-weight: 600; margin-bottom: .25rem; }
        input, select { width: 100%; padding: .5rem; border: 1px solid #ccc; font-size: .875rem; box-sizing: border-box; }
        .error { color: #991b1b; font-size: .8rem; margin-top: .25rem; }
        .btn { display: inline-block; padding: .5rem 1rem; font-size: .875rem; border: none; cursor: pointer; text-decoration: none; }
        .btn-primary { background: #111; color: #fff; }
        .btn-primary:hover { background: #333; }
        .btn-secondary { background: #e5e5e5; color: #111; }
        .btn-secondary:hover { background: #d4d4d4; }
        .actions { display: flex; gap: .5rem; margin-top: 1.5rem; }
    </style>
</head>
<body>
    @include('partials.nav')
    <div class="container">
        <h1>Tambah Jadwal Dokter</h1>

        <form method="POST" action="{{ role_route('jadwal-dokter.store') }}">
            @csrf
            <div class="form-group">
                <label for="doctor_id">Dokter</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">Pilih Dokter</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('doctor_id') == $d->id ? 'selected' : '' }}>{{ $d->name }} ({{ $d->polyclinic->name ?? '-' }})</option>
                    @endforeach
                </select>
                @error('doctor_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="day_of_week">Hari</label>
                <select name="day_of_week" id="day_of_week" required>
                    <option value="">Pilih Hari</option>
                    @foreach($days as $d)
                        <option value="{{ $d }}" {{ old('day_of_week') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @error('day_of_week')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="start_time">Jam Mulai</label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
                @error('start_time')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="end_time">Jam Selesai</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
                @error('end_time')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="max_patients">Maks Pasien</label>
                <input type="number" name="max_patients" id="max_patients" value="{{ old('max_patients', 20) }}" min="1" max="100" required>
                @error('max_patients')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ role_route('jadwal-dokter.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>

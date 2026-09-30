<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pasien</title>
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
        input, select, textarea { width: 100%; padding: .5rem; border: 1px solid #ccc; font-size: .875rem; box-sizing: border-box; }
        .error { color: #991b1b; font-size: .8rem; margin-top: .25rem; }
        .btn { display: inline-block; padding: .5rem 1rem; font-size: .875rem; border: none; cursor: pointer; text-decoration: none; }
        .btn-primary { background: #111; color: #fff; }
        .btn-primary:hover { background: #333; }
        .btn-secondary { background: #e5e5e5; color: #111; }
        .btn-secondary:hover { background: #d4d4d4; }
        .actions { display: flex; gap: .5rem; margin-top: 1.5rem; }
        .checkbox-group { display: flex; align-items: center; gap: .5rem; }
        .checkbox-group input { width: auto; }
    </style>
</head>
<body>
    @include('partials.nav')
    <div class="container">
        <h1>Edit Pasien</h1>

        <form method="POST" action="{{ role_route('pasien.update', $pasien) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $pasien->name) }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="nik">NIK (16 digit)</label>
                <input type="text" name="nik" id="nik" value="{{ old('nik', $pasien->nik) }}" maxlength="16" pattern="\d{16}">
                @error('nik')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="birth_date">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $pasien->birth_date?->format('Y-m-d')) }}">
                @error('birth_date')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="gender">Jenis Kelamin</label>
                <select name="gender" id="gender">
                    <option value="">Pilih</option>
                    <option value="L" {{ old('gender', $pasien->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $pasien->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="phone">Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $pasien->phone) }}">
                @error('phone')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea name="address" id="address" rows="3">{{ old('address', $pasien->address) }}</textarea>
                @error('address')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $pasien->is_active) ? 'checked' : '' }}>
                    <label for="is_active" style="margin:0">Aktif</label>
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ role_route('pasien.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>

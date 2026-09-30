<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokter</title>
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
        .checkbox-group { display: flex; align-items: center; gap: .5rem; }
        .checkbox-group input { width: auto; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="/admin/dashboard">Dashboard</a>
            <a href="{{ route('dokters.index') }}">Dokter</a>
            <a href="{{ route('polikliniks.index') }}">Poliklinik</a>
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
        <h1>Edit Dokter</h1>

        <form method="POST" action="{{ route('dokters.update', $dokter) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Dokter</label>
                <input type="text" name="name" id="name" value="{{ old('name', $dokter->name) }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="polyclinic_id">Poli</label>
                <select name="polyclinic_id" id="polyclinic_id" required>
                    <option value="">Pilih Poli</option>
                    @foreach($polyclinics as $p)
                        <option value="{{ $p->id }}" {{ old('polyclinic_id', $dokter->polyclinic_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('polyclinic_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="sip_no">No. SIP</label>
                <input type="text" name="sip_no" id="sip_no" value="{{ old('sip_no', $dokter->sip_no) }}">
                @error('sip_no')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="phone">Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $dokter->phone) }}">
                @error('phone')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $dokter->is_active) ? 'checked' : '' }}>
                    <label for="is_active" style="margin:0">Aktif</label>
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('dokters.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>

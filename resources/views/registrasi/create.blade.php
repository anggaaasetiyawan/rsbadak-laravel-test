<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Registrasi</title>
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
        input { width: 100%; padding: .5rem; border: 1px solid #ccc; font-size: .875rem; box-sizing: border-box; }
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
        <h1>Tambah Registrasi</h1>

        <form method="POST" action="{{ route('registrasi.store') }}">
            @csrf
            <div class="form-group">
                <label for="patient_id">Pasien</label>
                <select name="patient_id" id="patient_id" required>
                    <option value="">Pilih Pasien</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                    @endforeach
                </select>
                @error('patient_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="visit_date">Tanggal Kunjungan</label>
                <input type="date" name="visit_date" id="visit_date" value="{{ old('visit_date') }}" required>
                @error('visit_date')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="polyclinic_id">Poliklinik</label>
                <select name="polyclinic_id" id="polyclinic_id" required>
                    <option value="">Pilih Poliklinik</option>
                    @foreach($polyclinics as $polyclinic)
                        <option value="{{ $polyclinic->id }}" {{ old('polyclinic_id') == $polyclinic->id ? 'selected' : '' }}>{{ $polyclinic->name }}</option>
                    @endforeach
                </select>
                @error('polyclinic_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="doctor_id">Dokter</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">Pilih Dokter</option>
                    @foreach($doctorsData as $doctor)
                        <option value="{{ $doctor['id'] }}" data-polyclinic="{{ $doctor['polyclinic_id'] }}" {{ old('doctor_id') == $doctor['id'] ? 'selected' : '' }}>{{ $doctor['name'] }}</option>
                    @endforeach
                </select>
                @error('doctor_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="complaint">Keluhan</label>
                <textarea name="complaint" id="complaint" rows="4">{{ old('complaint') }}</textarea>
                @error('complaint')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('registrasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('polyclinic_id').addEventListener('change', function() {
            const poliId = this.value;
            const doctorSelect = document.getElementById('doctor_id');
            const options = doctorSelect.querySelectorAll('option[data-polyclinic]');
            doctorSelect.value = '';
            options.forEach(opt => {
                opt.style.display = (!poliId || opt.dataset.polyclinic === poliId) ? '' : 'none';
            });
        });
    </script>
</body>
</html>

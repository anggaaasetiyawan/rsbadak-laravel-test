<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use App\Models\Dokter;
use App\Models\Polyclinic;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index(Request $request)
    {
        $query = DoctorSchedule::with('doctor.polyclinic');

        if ($search = $request->input('search')) {
            $query->whereHas('doctor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($polyclinic_id = $request->input('polyclinic_id')) {
            $query->whereHas('doctor', function ($q) use ($polyclinic_id) {
                $q->where('polyclinic_id', $polyclinic_id);
            });
        }

        if ($day = $request->input('day_of_week')) {
            $query->where('day_of_week', $day);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->paginate(20)->withQueryString();
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('jadwal-dokter.index', compact('schedules', 'polyclinics', 'days'));
    }

    public function create()
    {
        $doctors = Dokter::where('is_active', true)->with('polyclinic')->orderBy('name')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('jadwal-dokter.create', compact('doctors', 'days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:dokters,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1|max:100',
        ]);

        $exists = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['day_of_week' => 'Jadwal untuk dokter dan hari tersebut sudah ada.'])->withInput();
        }

        $validated['is_active'] = true;
        DoctorSchedule::create($validated);

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function edit(DoctorSchedule $jadwal_dokter)
    {
        $doctors = Dokter::where('is_active', true)->with('polyclinic')->orderBy('name')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('jadwal-dokter.edit', compact('jadwal_dokter', 'doctors', 'days'));
    }

    public function update(Request $request, DoctorSchedule $jadwal_dokter)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:dokters,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $jadwal_dokter->update($validated);

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(DoctorSchedule $jadwal_dokter)
    {
        $jadwal_dokter->delete();

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}

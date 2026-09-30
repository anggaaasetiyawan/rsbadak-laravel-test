<?php

namespace App\Http\Controllers;

use App\Models\Registrasi;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Dokter;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Registrasi::with(['patient', 'polyclinic', 'doctor', 'registeredBy']);

        if ($search = $request->input('search')) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('medical_record_no', 'like', "%{$search}%");
            })->orWhere('registration_no', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($polyclinic_id = $request->input('polyclinic_id')) {
            $query->where('polyclinic_id', $polyclinic_id);
        }

        if ($date = $request->input('visit_date')) {
            $query->whereDate('visit_date', $date);
        }

        $registrasi = $query->orderByDesc('visit_date')->orderByDesc('created_at')->paginate(15)->withQueryString();
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();

        return view('registrasi.index', compact('registrasi', 'polyclinics'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $polyclinics = Polyclinic::where('is_active', true)->with(['dokters' => fn($q) => $q->where('is_active', true)])->orderBy('name')->get();
        $doctorsData = $polyclinics->flatMap(fn($p) => $p->dokters->map(fn($d) => ['id' => $d->id, 'name' => $d->name, 'polyclinic_id' => $d->polyclinic_id]))->values();
        return view('registrasi.create', compact('patients', 'polyclinics', 'doctorsData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'doctor_id' => 'required|exists:dokters,id',
            'complaint' => 'nullable|string|max:500',
        ]);

        // Validate doctor belongs to polyclinic
        $doctor = Dokter::findOrFail($validated['doctor_id']);
        if ($doctor->polyclinic_id != $validated['polyclinic_id']) {
            return back()->withErrors(['doctor_id' => 'Dokter tidak sesuai dengan poli yang dipilih.'])->withInput();
        }

        // Validate doctor has schedule on that day
        $dayName = \Carbon\Carbon::parse($validated['visit_date'])->locale('id')->dayName;
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $dayOfWeek = $dayMap[$dayName] ?? $dayName;

        $hasSchedule = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->exists();

        if (!$hasSchedule) {
            return back()->withErrors(['doctor_id' => 'Dokter tidak memiliki jadwal aktif pada hari tersebut.'])->withInput();
        }

        // Check duplicate active registration
        $duplicate = Registrasi::where('patient_id', $validated['patient_id'])
            ->where('doctor_id', $validated['doctor_id'])
            ->whereDate('visit_date', $validated['visit_date'])
            ->whereIn('status', ['terdaftar'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['patient_id' => 'Pasien sudah memiliki pendaftaran aktif untuk dokter dan tanggal yang sama.'])->withInput();
        }

        $validated['registration_no'] = Registrasi::generateRegistrationNo();
        $validated['queue_no'] = Registrasi::getNextQueueNo($validated['polyclinic_id'], $validated['visit_date']);
        $validated['registered_by'] = Auth::id();
        $validated['status'] = 'terdaftar';

        $registration = Registrasi::create($validated);
        return redirect()->route('registrasi.show', $registration)->with('success', 'Pendaftaran berhasil dibuat.');
    }

    public function show(Registrasi $registrasi)
    {
        $registrasi->load(['patient', 'polyclinic', 'doctor', 'registeredBy']);
        return view('registrasi.show', compact('registrasi'));
    }

    public function edit(Registrasi $registrasi)
    {
        if (in_array($registrasi->status, ['selesai', 'dibatalkan'])) {
            if (!Auth::user()->isAdmin()) {
                return redirect()->route('registrasi.show', $registrasi)->with('error', 'Pendaftaran dengan status selesai/dibatalkan hanya dapat diubah oleh admin.');
            }
        }

        $patients = Patient::orderBy('name')->get();
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();
        $doctors = Dokter::where('is_active', true)->with('polyclinic')->orderBy('name')->get();

        return view('registrasi.edit', compact('registrasi', 'patients', 'polyclinics', 'doctors'));
    }

    public function update(Request $request, Registrasi $registrasi)
    {
        if (in_array($registrasi->status, ['selesai', 'dibatalkan']) && !Auth::user()->isAdmin()) {
            return redirect()->route('registrasi.show', $registrasi)->with('error', 'Pendaftaran dengan status selesai/dibatalkan hanya dapat diubah oleh admin.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'doctor_id' => 'required|exists:dokters,id',
            'complaint' => 'nullable|string|max:500',
            'status' => 'required|in:terdaftar,selesai,dibatalkan',
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        if ($validated['status'] === 'dibatalkan' && $registrasi->status !== 'dibatalkan') {
            $validated['cancelled_at'] = now();
        }

        $registrasi->update($validated);
        return redirect()->route('registrasi.show', $registrasi)->with('success', 'Pendaftaran berhasil diperbarui.');
    }

    public function cancel(Request $request, Registrasi $registrasi)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $registrasi->update([
            'status' => 'dibatalkan',
            'cancelled_at' => now(),
            'cancellation_reason' => $validated['cancellation_reason'] ?? null,
        ]);

        return redirect()->route('registrasi.show', $registrasi)->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    public function print(Registrasi $registrasi)
    {
        $registrasi->load(['patient', 'polyclinic', 'doctor', 'registeredBy']);
        return view('registrasi.print', compact('registrasi'));
    }
}



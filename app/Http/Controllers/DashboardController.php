<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Registrasi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        $today = Carbon::today();

        $stats = [
            'total_pasien' => Patient::count(),
            'pasien_aktif' => Patient::where('is_active', true)->count(),
            'total_dokter' => Dokter::count(),
            'dokter_aktif' => Dokter::where('is_active', true)->count(),
            'total_poliklinik' => Polyclinic::count(),
            'poliklinik_aktif' => Polyclinic::where('is_active', true)->count(),
            'total_user' => User::count(),
            'registrasi_hari_ini' => Registrasi::whereDate('visit_date', $today)->count(),
            'registrasi_terdaftar' => Registrasi::where('status', 'terdaftar')->count(),
            'registrasi_selesai' => Registrasi::where('status', 'selesai')->count(),
            'registrasi_dibatalkan' => Registrasi::where('status', 'dibatalkan')->count(),
            'total_registrasi' => Registrasi::count(),
            'jadwal_aktif_hari_ini' => DoctorSchedule::where('day_of_week', $this->getDayName($today))
                ->where('is_active', true)->count(),
        ];

        // Registrasi per poliklinik (untuk chart)
        $registrasiPerPoli = Registrasi::selectRaw('polyclinic_id, COUNT(*) as total')
            ->groupBy('polyclinic_id')
            ->with('polyclinic:id,name')
            ->get()
            ->map(fn($item) => [
                'name' => $item->polyclinic->name ?? '-',
                'total' => $item->total,
            ]);

        // 5 registrasi terbaru
        $recentRegistrations = Registrasi::with(['patient', 'polyclinic', 'doctor', 'registeredBy'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Registrasi 7 hari terakhir
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'date' => $date->format('d M'),
                'total' => Registrasi::whereDate('visit_date', $date)->count(),
            ];
        });

        return view('dashboard', compact('stats', 'registrasiPerPoli', 'recentRegistrations', 'last7Days'));
    }

    public function petugas()
    {
        $today = Carbon::today();

        $stats = [
            'total_pasien' => Patient::where('is_active', true)->count(),
            'registrasi_hari_ini' => Registrasi::whereDate('visit_date', $today)->count(),
            'registrasi_terdaftar' => Registrasi::where('status', 'terdaftar')->count(),
            'registrasi_selesai' => Registrasi::where('status', 'selesai')->count(),
            'total_registrasi' => Registrasi::count(),
            'jadwal_aktif_hari_ini' => DoctorSchedule::where('day_of_week', $this->getDayName($today))
                ->where('is_active', true)->count(),
        ];

        // 5 registrasi terbaru
        $recentRegistrations = Registrasi::with(['patient', 'polyclinic', 'doctor', 'registeredBy'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentRegistrations'));
    }

    private function getDayName(Carbon $date): string
    {
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        return $dayMap[$date->format('l')] ?? $date->format('l');
    }
}

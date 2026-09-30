<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Polyclinic;
use App\Models\Dokter;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Registrasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === USERS ===
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@rs.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $petugas = User::create([
            'name' => 'Petugas Pendaftaran',
            'email' => 'petugas@rs.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        // === POLIKLINIKS ===
        $poliklinik1 = \App\Models\Polyclinic::create([
            'name' => 'Poliklinik Umum',
            'is_active' => true,
        ]);

        $poliklinik2 = \App\Models\Polyclinic::create([
            'name' => 'Poliklinik Gigi',
            'is_active' => true,
        ]);

        // === DOKTERS ===
        $dokter1 = \App\Models\Dokter::create([
            'polyclinic_id' => $poliklinik1->id,
            'sip_no' => 'SIP123456',
            'name' => 'Dr. Andi Wijaya',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $dokter2 = \App\Models\Dokter::create([
            'polyclinic_id' => $poliklinik2->id,
            'sip_no' => 'SIP654321',
            'name' => 'Dr. Siti Aminah',
            'phone' => '081987654321',
            'is_active' => true,
        ]);

        // === JADWAL DOKTERS ===
        \App\Models\DoctorSchedule::create([
            'doctor_id' => $dokter1->id,
            'day_of_week' => 'Senin',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'max_patients' => 10,
            'is_active' => true,
            'created_at' => now(),
        ]);

        \App\Models\DoctorSchedule::create([
            'doctor_id' => $dokter2->id,
            'day_of_week' => 'Selasa',
            'start_time' => '13:00:00',
            'end_time' => '17:00:00',
            'max_patients' => 10,
            'is_active' => true,
            'created_at' => now(),
        ]);

        // === PASIENS ===
        $pasien1 = \App\Models\Patient::create([
            'medical_record_no' => 'MRN001',
            'name' => 'Budi Santoso',
            'nik' => '3212345678901234',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 1, Jakarta',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $pasien2 = \App\Models\Patient::create([
            'medical_record_no' => 'MRN002',
            'name' => 'Siti Aminah',
            'nik' => '3212345678901235',
            'birth_date' => '1992-02-02',
            'gender' => 'P',
            'phone' => '081987654321',
            'address' => 'Jl. Sudirman No. 2, Jakarta',
            'is_active' => true,
            'created_at' => now(),
        ]);

        // === REGISTRASI ===
        \App\Models\Registrasi::create([
            'registration_no' => 'REG001',
            'patient_id' => $pasien1->id,
            'polyclinic_id' => $poliklinik1->id,
            'doctor_id' => $dokter1->id,
            'registered_by' => $admin->id,
            'visit_date' => now()->addDays(1),
            'queue_no' => 1,
            'complaint' => 'Demam dan batuk',
            'status' => 'terdaftar',
        ]);

        \App\Models\Registrasi::create([
            'registration_no' => 'REG002',
            'patient_id' => $pasien2->id,
            'polyclinic_id' => $poliklinik2->id,
            'doctor_id' => $dokter2->id,
            'registered_by' => $petugas->id,
            'visit_date' => now()->addDays(2),
            'queue_no' => 1,
            'complaint' => 'Sakit gigi',
            'status' => 'terdaftar',
        ]);



    }
}

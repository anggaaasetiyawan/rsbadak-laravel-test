<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;

    protected $table = 'registrasi';

    protected $fillable = [
        'registration_no',
        'patient_id',
        'polyclinic_id',
        'doctor_id',
        'registered_by',
        'visit_date',
        'queue_no',
        'complaint',
        'status',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'cancelled_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function polyclinic()
    {
        return $this->belongsTo(Polyclinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Dokter::class, 'doctor_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public static function generateRegistrationNo(): string
    {
        $today = date('Ymd');
        $prefix = 'REG-' . $today;
        $last = self::where('registration_no', 'like', $prefix . '-%')
            ->orderBy('registration_no', 'desc')
            ->first();
        $number = $last ? (int) substr($last->registration_no, -4) + 1 : 1;
        return sprintf('%s-%04d', $prefix, $number);
    }

    public static function getNextQueueNo(int $polyclinicId, string $visitDate): int
    {
        $last = self::where('polyclinic_id', $polyclinicId)
            ->where('visit_date', $visitDate)
            ->max('queue_no');
        return $last ? $last + 1 : 1;
    }
}

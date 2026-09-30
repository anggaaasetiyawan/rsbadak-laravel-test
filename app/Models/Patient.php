<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_no',
        'name',
        'nik',
        'birth_date',
        'gender',
        'phone',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(Registrasi::class);
    }

    public static function generateMRN(): string
    {
        $today = date('Ymd');
        $prefix = 'RM-' . $today;
        $last = self::where('medical_record_no', 'like', $prefix . '-%')
            ->orderBy('medical_record_no', 'desc')
            ->first();
        $number = $last ? (int) substr($last->medical_record_no, -4) + 1 : 1;
        return sprintf('%s-%04d', $prefix, $number);
    }
}

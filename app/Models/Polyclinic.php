<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Polyclinic extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function dokters()
    {
        return $this->hasMany(Dokter::class);
    }
}

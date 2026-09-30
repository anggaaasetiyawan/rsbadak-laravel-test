<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrasi', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('polyclinic_id')->constrained('polyclinics');
            $table->foreignId('doctor_id')->constrained('dokters');
            $table->foreignId('registered_by')->constrained('users');
            $table->date('visit_date');
            $table->integer('queue_no');
            $table->text('complaint')->nullable();
            $table->enum('status', ['terdaftar', 'selesai', 'dibatalkan'])->default('terdaftar');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->unique(['visit_date', 'polyclinic_id', 'queue_no']);
            $table->index(['patient_id', 'doctor_id', 'visit_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrasi');
    }
};

<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('medical_record_no', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
        }

        $pasien = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('pasien.index', compact('pasien'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:patients,nik',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['medical_record_no'] = Patient::generateMRN();
        $validated['is_active'] = true;

        Patient::create($validated);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function edit(Patient $pasien)
    {
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Patient $pasien)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:patients,nik,' . $pasien->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $pasien->update($validated);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $pasien)
    {
        if ($pasien->registrations()->where('status', 'terdaftar')->exists()) {
            return back()->with('error', 'Pasien memiliki pendaftaran aktif, tidak dapat dihapus.');
        }

        $pasien->update(['is_active' => false]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dinonaktifkan.');
    }
}

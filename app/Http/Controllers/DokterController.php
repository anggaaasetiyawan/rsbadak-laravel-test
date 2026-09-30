<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Polyclinic;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokter::with('polyclinic');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sip_no', 'like', "%{$search}%");
        }

        if ($polyclinic_id = $request->input('polyclinic_id')) {
            $query->where('polyclinic_id', $polyclinic_id);
        }

        $dokter = $query->orderBy('name')->paginate(15)->withQueryString();
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();

        return view('dokters.index', compact('dokter', 'polyclinics'));
    }

    public function create()
    {
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();
        return view('dokters.create', compact('polyclinics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'sip_no' => 'nullable|string|max:50|unique:dokters,sip_no',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['is_active'] = true;
        dokter::create($validated);

        return redirect()->route('dokters.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function edit(dokter $dokter)
    {
        $polyclinics = Polyclinic::where('is_active', true)->orderBy('name')->get();
        return view('dokters.edit', compact('dokter', 'polyclinics'));
    }

    public function update(Request $request, dokter $dokter)
    {
        $validated = $request->validate([
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'sip_no' => 'nullable|string|max:50|unique:dokter,sip_no,' . $dokter->id,
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $dokter->update($validated);
        return redirect()->route('dokters.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(dokter $dokter)
    {
        if ($dokter->registrations()->exists()) {
            $dokter->update(['is_active' => false]);
            return redirect()->route('dokters.index')->with('success', 'dokter dinonaktifkan karena masih memiliki data terkait.');
        }
        $dokter->delete();
        return redirect()->route('dokters.index')->with('success', 'Data dokter berhasil dihapus.');
    }
}

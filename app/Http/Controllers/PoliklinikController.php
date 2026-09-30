<?php

namespace App\Http\Controllers;

use App\Models\Polyclinic;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index(Request $request)
    {
        $query = Polyclinic::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $polyclinics = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('polikliniks.index', compact('polyclinics'));
    }

    public function create()
    {
        return view('polikliniks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:polyclinics,name',
        ]);

        $validated['is_active'] = true;
        Polyclinic::create($validated);

        return redirect()->route('polikliniks.index')->with('success', 'Data poliklinik berhasil ditambahkan.');
    }

    public function edit(Polyclinic $poliklinik)
    {
        return view('polikliniks.edit', compact('poliklinik'));
    }

    public function update(Request $request, Polyclinic $poliklinik)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:polyclinics,name,' . $poliklinik->id,
            'is_active' => 'boolean',
        ]);

        $poliklinik->update($validated);

        return redirect()->route('polikliniks.index')->with('success', 'Data poliklinik berhasil diperbarui.');
    }

    public function destroy(Polyclinic $poliklinik)
    {
        if ($poliklinik->dokters()->exists()) {
            $poliklinik->update(['is_active' => false]);
            return redirect()->route('polikliniks.index')->with('success', 'Poliklinik dinonaktifkan karena masih memiliki data dokter terkait.');
        }

        $poliklinik->delete();

        return redirect()->route('polikliniks.index')->with('success', 'Data poliklinik berhasil dihapus.');
    }
}

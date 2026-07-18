<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PerangkatDesaController extends Controller
{
    public function index(): View
    {
        $perangkatDesaList = PerangkatDesa::orderBy('id', 'asc')->paginate(15);
        return view('admin.perangkat-desa.index', compact('perangkatDesaList'));
    }

    public function create(): View
    {
        return view('admin.perangkat-desa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=250,min_height=300',
            'is_active' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama perangkat desa wajib diisi.',
            'jabatan.required' => 'Jabatan perangkat desa wajib diisi.',
            'foto.dimensions' => 'Resolusi foto terlalu kecil! Minimal lebar 250px dan tinggi 300px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/perangkat-desa', 'public');
            $data['foto'] = $path;
        }

        PerangkatDesa::create($data);

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil ditambahkan!');
    }

    public function edit(PerangkatDesa $perangkatDesa): View
    {
        return view('admin.perangkat-desa.edit', compact('perangkatDesa'));
    }

    public function update(Request $request, PerangkatDesa $perangkatDesa): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=250,min_height=300',
            'is_active' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama perangkat desa wajib diisi.',
            'jabatan.required' => 'Jabatan perangkat desa wajib diisi.',
            'foto.dimensions' => 'Resolusi foto terlalu kecil! Minimal lebar 250px dan tinggi 300px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
                Storage::disk('public')->delete($perangkatDesa->foto);
            }
            $path = $request->file('foto')->store('images/perangkat-desa', 'public');
            $data['foto'] = $path;
        }

        $perangkatDesa->update($data);

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil diperbarui!');
    }

    public function destroy(PerangkatDesa $perangkatDesa): RedirectResponse
    {
        if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
            Storage::disk('public')->delete($perangkatDesa->foto);
        }
        $perangkatDesa->delete();

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        $fields = ['nama', 'jabatan', 'nip'];
        foreach ($fields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                if (in_array($field, ['nama', 'jabatan'], true)) {
                    $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                }
                $request->merge([$field => $cleaned]);
            }
        }
    }
}
                                            
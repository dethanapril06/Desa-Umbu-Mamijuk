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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama perangkat desa wajib diisi.',
            'jabatan.required' => 'Jabatan perangkat desa wajib diisi.',
            'foto.max' => 'Ukuran file foto maksimal 10 MB.',
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama perangkat desa wajib diisi.',
            'jabatan.required' => 'Jabatan perangkat desa wajib diisi.',
            'foto.max' => 'Ukuran file foto maksimal 10 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto', 'delete_foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->has('delete_foto') && $request->delete_foto == '1') {
            if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
                Storage::disk('public')->delete($perangkatDesa->foto);
            }
            $data['foto'] = null;
        } elseif ($request->hasFile('foto')) {
            if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
                Storage::disk('public')->delete($perangkatDesa->foto);
            }
            $path = $request->file('foto')->store('images/perangkat-desa', 'public');
            $data['foto'] = $path;
        }

        $perangkatDesa->update($data);

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil diperbarui!');
    }

    public function deleteFoto(PerangkatDesa $perangkatDesa): RedirectResponse
    {
        if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
            Storage::disk('public')->delete($perangkatDesa->foto);
        }
        $perangkatDesa->update(['foto' => null]);

        return redirect()->back()->with('success', 'Foto perangkat desa berhasil dihapus!');
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
     * Kapital huruf pertama setelah setiap karakter non-alphanumeric.
     * Contoh: "sekretaris desa" → "Sekretaris Desa"
     */
    private function toCapitalEachWord(string $str): string
    {
        $str = mb_strtolower($str, 'UTF-8');
        return preg_replace_callback(
            '/(^|[^a-zA-Z0-9\x{00C0}-\x{024F}\x{1E00}-\x{1EFF}]+)([a-zA-Z\x{00C0}-\x{024F}\x{1E00}-\x{1EFF}])/u',
            fn($m) => $m[1] . mb_strtoupper($m[2], 'UTF-8'),
            $str
        );
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        // nama & jabatan: trim + collapse spasi + Capital Each Word
        foreach (['nama', 'jabatan'] as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = $this->toCapitalEachWord($cleaned);
                $request->merge([$field => $cleaned]);
            }
        }

        // nip: strip semua non-digit (tidak boleh ada spasi)
        if ($request->has('nip') && !empty($request->input('nip'))) {
            $cleaned = preg_replace('/\D+/', '', (string) $request->input('nip'));
            $request->merge(['nip' => $cleaned]);
        }
    }
}
                                            
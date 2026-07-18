<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenginapanWisata;
use App\Rules\LandscapeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class PenginapanWisataController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'nama_penginapan' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:100',
            'kisaran_harga' => 'nullable|string|max:100',
            'jarak' => 'nullable|string|max:100',
            'no_telepon' => 'nullable|string|max:50',
            'fasilitas_singkat' => 'nullable|string|max:255',
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
        ], [
            'nama_penginapan.required' => 'Nama penginapan wajib diisi.',
            'wisata_id.required' => 'Destinasi wisata wajib dipilih.',
            'wisata_id.exists' => 'Destinasi wisata tidak valid.',
            'foto.dimensions' => 'Resolusi foto penginapan terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/wisata/penginapan', 'public');
            $data['foto'] = $path;
        }

        PenginapanWisata::create($data);

        return redirect()->to(route('admin.wisata.edit', $request->wisata_id) . '#tab-penginapan')->with('success', 'Info penginapan berhasil ditambahkan!');
    }

    public function destroy(PenginapanWisata $penginapan): RedirectResponse
    {
        $wisataId = $penginapan->wisata_id;

        if ($penginapan->foto && Storage::disk('public')->exists($penginapan->foto)) {
            Storage::disk('public')->delete($penginapan->foto);
        }

        $penginapan->delete();

        return redirect()->to(route('admin.wisata.edit', $wisataId) . '#tab-penginapan')->with('success', 'Info penginapan berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('nama_penginapan') && is_string($request->input('nama_penginapan')) && !empty($request->input('nama_penginapan'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('nama_penginapan')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['nama_penginapan' => $cleaned]);
        }

        $stringFields = ['kisaran_harga', 'jarak', 'no_telepon', 'fasilitas_singkat'];
        foreach ($stringFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                if ($field === 'kisaran_harga') {
                    $cleaned = \App\Models\Penginapan::formatHarga($cleaned);
                }
                $request->merge([$field => $cleaned]);
            }
        }
    }
}

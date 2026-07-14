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
        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'nama_penginapan' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:100',
            'kisaran_harga' => 'nullable|string|max:100',
            'jarak' => 'nullable|string|max:100',
            'no_telepon' => 'nullable|string|max:50',
            'fasilitas_singkat' => 'nullable|string|max:255',
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'urutan' => 'nullable|integer|min:0',
        ], [
            'foto.dimensions' => 'Resolusi foto penginapan terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);
        $data['urutan'] = $request->input('urutan', 0);

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
}

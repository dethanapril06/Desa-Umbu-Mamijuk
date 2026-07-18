<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use App\Rules\LandscapeImage;

class GaleriWisataController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption' => 'nullable|string|max:255',
        ], [
            'gambar.required' => 'Foto galeri wajib diunggah.',
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
            'wisata_id.required' => 'Destinasi wisata wajib dipilih.',
            'wisata_id.exists' => 'Destinasi wisata tidak valid.',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images/wisata/galeri', 'public');
            $data['gambar'] = $path;
        }

        GaleriWisata::create($data);

        return redirect()->to(route('admin.wisata.edit', $request->wisata_id) . '#tab-galeri')->with('success', 'Foto galeri wisata berhasil ditambahkan!');
    }

    public function destroy(GaleriWisata $galeri): RedirectResponse
    {
        $wisataId = $galeri->wisata_id;

        if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
            Storage::disk('public')->delete($galeri->gambar);
        }

        $galeri->delete();

        return redirect()->to(route('admin.wisata.edit', $wisataId) . '#tab-galeri')->with('success', 'Foto galeri wisata berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('caption') && is_string($request->input('caption')) && !empty($request->input('caption'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('caption')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['caption' => $cleaned]);
        }
    }
}

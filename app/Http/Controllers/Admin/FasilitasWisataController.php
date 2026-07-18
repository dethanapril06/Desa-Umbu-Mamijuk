<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FasilitasWisata;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FasilitasWisataController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'wisata_id.required' => 'Destinasi wisata wajib dipilih.',
            'wisata_id.exists' => 'Destinasi wisata tidak valid.',
        ]);

        FasilitasWisata::create($request->all());

        return redirect()->to(route('admin.wisata.edit', $request->wisata_id) . '#tab-fasilitas')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function destroy(FasilitasWisata $fasilitas): RedirectResponse
    {
        $wisataId = $fasilitas->wisata_id;
        $fasilitas->delete();

        return redirect()->to(route('admin.wisata.edit', $wisataId) . '#tab-fasilitas')->with('success', 'Fasilitas berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('nama') && is_string($request->input('nama')) && !empty($request->input('nama'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('nama')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['nama' => $cleaned]);
        }
    }
}

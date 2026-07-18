<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipsWisata;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TipsWisataController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ], [
            'judul.required' => 'Judul tips kunjungan wajib diisi.',
            'wisata_id.required' => 'Destinasi wisata wajib dipilih.',
            'wisata_id.exists' => 'Destinasi wisata tidak valid.',
        ]);

        TipsWisata::create($request->all());

        return redirect()->to(route('admin.wisata.edit', $request->wisata_id) . '#tab-tips')->with('success', 'Tips wisata berhasil ditambahkan!');
    }

    public function destroy(TipsWisata $tips): RedirectResponse
    {
        $wisataId = $tips->wisata_id;
        $tips->delete();

        return redirect()->to(route('admin.wisata.edit', $wisataId) . '#tab-tips')->with('success', 'Tips wisata berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('judul') && is_string($request->input('judul')) && !empty($request->input('judul'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('judul')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['judul' => $cleaned]);
        }

        if ($request->has('deskripsi') && is_string($request->input('deskripsi')) && !empty($request->input('deskripsi'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('deskripsi')));
            $request->merge(['deskripsi' => $cleaned]);
        }
    }
}

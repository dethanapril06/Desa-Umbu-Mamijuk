<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuteWisata;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RuteWisataController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'jenis_transportasi' => 'required|string|max:100', // e.g. jalan kaki, mobil, motor
            'icon' => 'nullable|string|max:100', // e.g. bx-walk, bx-car
            'deskripsi' => 'required|string',
            'warna_badge' => 'required|string|max:50', // e.g. success, info, primary
        ], [
            'jenis_transportasi.required' => 'Jenis transportasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi rute perjalanan wajib diisi.',
            'warna_badge.required' => 'Warna badge wajib dipilih.',
            'wisata_id.required' => 'Destinasi wisata wajib dipilih.',
            'wisata_id.exists' => 'Destinasi wisata tidak valid.',
        ]);

        RuteWisata::create($request->all());

        return redirect()->to(route('admin.wisata.edit', $request->wisata_id) . '#tab-rute')->with('success', 'Rute wisata berhasil ditambahkan!');
    }

    public function destroy(RuteWisata $rute): RedirectResponse
    {
        $wisataId = $rute->wisata_id;
        $rute->delete();

        return redirect()->to(route('admin.wisata.edit', $wisataId) . '#tab-rute')->with('success', 'Rute wisata berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('jenis_transportasi') && is_string($request->input('jenis_transportasi')) && !empty($request->input('jenis_transportasi'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('jenis_transportasi')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['jenis_transportasi' => $cleaned]);
        }

        if ($request->has('deskripsi') && is_string($request->input('deskripsi')) && !empty($request->input('deskripsi'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('deskripsi')));
            $request->merge(['deskripsi' => $cleaned]);
        }
    }
}

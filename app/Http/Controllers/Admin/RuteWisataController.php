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
        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'jenis_transportasi' => 'required|string|max:100', // e.g. jalan kaki, mobil, motor
            'icon' => 'nullable|string|max:100', // e.g. bx-walk, bx-car
            'deskripsi' => 'nullable|string',
            'warna_badge' => 'nullable|string|max:50', // e.g. success, info, primary
            'urutan' => 'required|integer|min:0',
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
}

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
        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
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
}

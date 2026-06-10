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
        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
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
}

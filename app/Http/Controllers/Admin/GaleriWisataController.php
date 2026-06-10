<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class GaleriWisataController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'caption' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:0',
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
}

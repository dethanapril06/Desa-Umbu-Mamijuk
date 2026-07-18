<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\AlbumGaleri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Rules\LandscapeImage;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $albumId = $request->input('album_galeri_id');
        $query = Galeri::with('albumGaleri');

        if ($albumId) {
            $query->where('album_galeri_id', $albumId);
        }

        $photos = $query->orderBy('id', 'desc')->paginate(15);
        $albums = AlbumGaleri::all();

        return view('admin.galeri.index', compact('photos', 'albums', 'albumId'));
    }

    public function create(Request $request): View
    {
        $albums = AlbumGaleri::all();
        $selectedAlbumId = $request->input('album_galeri_id');

        return view('admin.galeri.create', compact('albums', 'selectedAlbumId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'album_galeri_id' => 'required|exists:album_galeri,id',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption' => 'nullable|string|max:255',
        ], [
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images/galeri', 'public');
            $data['gambar'] = $path;
        }

        Galeri::create($data);

        return redirect()->route('admin.galeri.index', ['album_galeri_id' => $request->album_galeri_id])->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    public function edit(Galeri $galeri): View
    {
        $albums = AlbumGaleri::all();
        return view('admin.galeri.edit', compact('galeri', 'albums'));
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        $request->validate([
            'album_galeri_id' => 'required|exists:album_galeri,id',
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption' => 'nullable|string|max:255',
        ], [
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $path = $request->file('gambar')->store('images/galeri', 'public');
            $data['gambar'] = $path;
        }

        $galeri->update($data);

        return redirect()->route('admin.galeri.index', ['album_galeri_id' => $request->album_galeri_id])->with('success', 'Foto galeri berhasil diperbarui!');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $albumId = $galeri->album_galeri_id;

        if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
            Storage::disk('public')->delete($galeri->gambar);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index', ['album_galeri_id' => $albumId])->with('success', 'Foto galeri berhasil dihapus!');
    }
}

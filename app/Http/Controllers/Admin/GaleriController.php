<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\AlbumGaleri;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $selectedAlbumId = $request->input('album_galeri_id');
        $albumId = $selectedAlbumId;
        $query = Galeri::with('albumGaleri');

        if ($selectedAlbumId) {
            $query->where('album_galeri_id', $selectedAlbumId);
        }

        $galeriList = $query->orderBy('id', 'desc')->paginate(12);
        $photos = $galeriList;
        $albums = AlbumGaleri::withCount('galeri')->get();

        return view('admin.galeri.index', compact('galeriList', 'photos', 'albums', 'selectedAlbumId', 'albumId'));
    }

    public function create(Request $request): View
    {
        $albums = AlbumGaleri::all();
        $selectedAlbumId = $request->input('album_galeri_id');
        $albumId = $selectedAlbumId;

        return view('admin.galeri.create', compact('albums', 'selectedAlbumId', 'albumId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'album_galeri_id' => 'required|exists:album_galeri,id',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'caption' => 'required|string|max:255',
        ], [
            'caption.required' => 'Keterangan foto wajib diisi.',
            'gambar.max' => 'Ukuran file gambar maksimal 10 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            $path = ImageService::processAndStore($request->file('gambar'), 'images/galeri');
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
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'caption' => 'required|string|max:255',
        ], [
            'caption.required' => 'Keterangan foto wajib diisi.',
            'gambar.max' => 'Ukuran file gambar maksimal 10 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $path = ImageService::processAndStore($request->file('gambar'), 'images/galeri');
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

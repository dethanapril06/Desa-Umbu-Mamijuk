<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumGaleri;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Rules\LandscapeImage;

class AlbumGaleriController extends Controller
{
    public function index(): View
    {
        $albums = AlbumGaleri::with('galeri')->orderBy('id', 'desc')->paginate(10);
        return view('admin.album-galeri.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.album-galeri.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:album_galeri,nama',
            'deskripsi' => 'nullable|string',
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
        ];

        $messages = [
            'nama.required' => 'Nama album wajib diisi.',
            'nama.unique' => 'Nama album tersebut sudah terdaftar di sistem.',
            'nama.max' => 'Nama album maksimal 255 karakter.',
            'cover.dimensions' => 'Resolusi cover terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'cover.max' => 'Ukuran file cover maksimal 2 MB.',
            'cover.mimes' => 'Format cover harus berupa JPEG, PNG, JPG, atau WEBP.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['cover']);
        $data['slug'] = Str::slug($request->nama);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('images/album', 'public');
            $data['cover'] = $path;
        }

        AlbumGaleri::create($data);

        return redirect()->route('admin.album-galeri.index')->with('success', 'Album galeri berhasil dibuat!');
    }

    public function edit(AlbumGaleri $albumGaleri): View
    {
        return view('admin.album-galeri.edit', compact('albumGaleri'));
    }

    public function update(Request $request, AlbumGaleri $albumGaleri): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:album_galeri,nama,' . $albumGaleri->id,
            'deskripsi' => 'nullable|string',
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
        ];

        $messages = [
            'nama.required' => 'Nama album wajib diisi.',
            'nama.unique' => 'Nama album tersebut sudah terdaftar di sistem.',
            'nama.max' => 'Nama album maksimal 255 karakter.',
            'cover.dimensions' => 'Resolusi cover terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'cover.max' => 'Ukuran file cover maksimal 2 MB.',
            'cover.mimes' => 'Format cover harus berupa JPEG, PNG, JPG, atau WEBP.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['cover']);
        $data['slug'] = Str::slug($request->nama);

        if ($request->hasFile('cover')) {
            if ($albumGaleri->cover && Storage::disk('public')->exists($albumGaleri->cover)) {
                Storage::disk('public')->delete($albumGaleri->cover);
            }
            $path = $request->file('cover')->store('images/album', 'public');
            $data['cover'] = $path;
        }

        $albumGaleri->update($data);

        return redirect()->route('admin.album-galeri.index')->with('success', 'Album galeri berhasil diperbarui!');
    }

    public function destroy(AlbumGaleri $albumGaleri): RedirectResponse
    {
        if ($albumGaleri->cover && Storage::disk('public')->exists($albumGaleri->cover)) {
            Storage::disk('public')->delete($albumGaleri->cover);
        }

        // Delete all photo files inside the album
        foreach ($albumGaleri->galeri as $photo) {
            if ($photo->gambar && Storage::disk('public')->exists($photo->gambar)) {
                Storage::disk('public')->delete($photo->gambar);
            }
            $photo->delete();
        }

        $albumGaleri->delete();
        return redirect()->route('admin.album-galeri.index')->with('success', 'Album galeri beserta semua fotonya berhasil dihapus!');
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

        if ($request->has('deskripsi') && is_string($request->input('deskripsi')) && !empty($request->input('deskripsi'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('deskripsi')));
            $request->merge(['deskripsi' => $cleaned]);
        }
    }
}

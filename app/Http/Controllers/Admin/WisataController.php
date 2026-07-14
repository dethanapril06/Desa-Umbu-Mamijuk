<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Rules\LandscapeImage;

class WisataController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori_id');

        $query = Wisata::with(['kategoriWisata', 'user']);

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi_singkat', 'like', "%{$search}%");
        }

        if ($kategori) {
            $query->where('kategori_wisata_id', $kategori);
        }

        $wisataList = $query->orderBy('id', 'desc')->paginate(10);
        $categories = KategoriWisata::all();

        return view('admin.wisata.index', compact('wisataList', 'categories', 'search', 'kategori'));
    }

    public function create(): View
    {
        $categories = KategoriWisata::all();
        return view('admin.wisata.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_wisata_id' => 'required|exists:kategori_wisata,id',
            'deskripsi_singkat' => 'nullable|string|max:1000',
            'deskripsi' => 'nullable|string',
            'highlight_quote' => 'nullable|string|max:500',
            'gambar_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'harga_tiket' => 'required|numeric|min:0',
            'harga_parkir_motor' => 'nullable|string|max:50',
            'harga_parkir_mobil' => 'nullable|string|max:50',
            'jam_operasional' => 'nullable|string|max:100',
            'hari_buka' => 'nullable|string|max:100',
            'jarak_dari_desa' => 'nullable|string|max:100',
            'durasi_trek' => 'nullable|string|max:100',
            'cocok_untuk' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'koordinat_lat' => 'nullable|string|max:50',
            'koordinat_lng' => 'nullable|string|max:50',
            'google_maps_embed_url' => 'nullable|string',
            'is_unggulan' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ], [
            'gambar_utama.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar_utama.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar_utama.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar_utama']);
        $data['user_id'] = auth()->id() ?? 1;
        $data['slug'] = Str::slug($request->nama) . '-' . time();
        $data['is_unggulan'] = $request->has('is_unggulan') ? (bool) $request->is_unggulan : false;
        $data['is_published'] = $request->has('is_published') ? (bool) $request->is_published : false;

        if ($request->hasFile('gambar_utama')) {
            $path = $request->file('gambar_utama')->store('images/wisata', 'public');
            $data['gambar_utama'] = $path;
        }

        $wisata = Wisata::create($data);

        return redirect()->route('admin.wisata.edit', $wisata->id)->with('success', 'Destinasi wisata berhasil disimpan! Silakan lengkapi fasilitas, tips, rute, dan galeri foto di halaman ini.');
    }

    public function edit(Wisata $wisata): View
    {
        $wisata->load(['galeriWisata', 'fasilitasWisata', 'tipsWisata', 'ruteWisata', 'ulasanWisata', 'penginapanWisata']);
        $categories = KategoriWisata::all();

        return view('admin.wisata.edit', compact('wisata', 'categories'));
    }

    public function update(Request $request, Wisata $wisata): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_wisata_id' => 'required|exists:kategori_wisata,id',
            'deskripsi_singkat' => 'nullable|string|max:1000',
            'deskripsi' => 'nullable|string',
            'highlight_quote' => 'nullable|string|max:500',
            'gambar_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'harga_tiket' => 'required|numeric|min:0',
            'harga_parkir_motor' => 'nullable|string|max:50',
            'harga_parkir_mobil' => 'nullable|string|max:50',
            'jam_operasional' => 'nullable|string|max:100',
            'hari_buka' => 'nullable|string|max:100',
            'jarak_dari_desa' => 'nullable|string|max:100',
            'durasi_trek' => 'nullable|string|max:100',
            'cocok_untuk' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'koordinat_lat' => 'nullable|string|max:50',
            'koordinat_lng' => 'nullable|string|max:50',
            'google_maps_embed_url' => 'nullable|string',
            'is_unggulan' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ], [
            'gambar_utama.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar_utama.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar_utama.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar_utama']);
        $data['is_unggulan'] = $request->has('is_unggulan') ? (bool) $request->is_unggulan : false;
        $data['is_published'] = $request->has('is_published') ? (bool) $request->is_published : false;

        if ($request->nama !== $wisata->nama) {
            $data['slug'] = Str::slug($request->nama) . '-' . time();
        }

        if ($request->hasFile('gambar_utama')) {
            if ($wisata->gambar_utama && Storage::disk('public')->exists($wisata->gambar_utama)) {
                Storage::disk('public')->delete($wisata->gambar_utama);
            }
            $path = $request->file('gambar_utama')->store('images/wisata', 'public');
            $data['gambar_utama'] = $path;
        }

        $wisata->update($data);

        return redirect()->route('admin.wisata.index')->with('success', 'Destinasi wisata berhasil diperbarui!');
    }

    public function destroy(Wisata $wisata): RedirectResponse
    {
        if ($wisata->gambar_utama && Storage::disk('public')->exists($wisata->gambar_utama)) {
            Storage::disk('public')->delete($wisata->gambar_utama);
        }

        // Delete all child photos in galeriWisata
        foreach ($wisata->galeriWisata as $g) {
            if ($g->gambar && Storage::disk('public')->exists($g->gambar)) {
                Storage::disk('public')->delete($g->gambar);
            }
            $g->delete();
        }

        $wisata->delete();

        return redirect()->route('admin.wisata.index')->with('success', 'Destinasi wisata berhasil dihapus!');
    }
}

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
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_wisata_id' => 'required|exists:kategori_wisata,id',
            'deskripsi_singkat' => 'required|string|max:1000',
            'deskripsi' => 'required|string',
            'highlight_quote' => 'required|string|max:500',
            'gambar_utama' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'harga_tiket' => 'required|numeric|min:0',
            'harga_parkir_motor' => 'required|string|max:50',
            'harga_parkir_mobil' => 'required|string|max:50',
            'jam_operasional' => 'required|string|max:100',
            'hari_buka' => 'required|string|max:100',
            'jarak_dari_desa' => 'required|string|max:100',
            'durasi_trek' => 'required|string|max:100',
            'cocok_untuk' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'google_maps_embed_url' => 'required|string',
            'is_unggulan' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama destinasi wisata wajib diisi.',
            'kategori_wisata_id.required' => 'Kategori wisata wajib dipilih.',
            'kategori_wisata_id.exists' => 'Kategori wisata yang dipilih tidak valid.',
            'deskripsi_singkat.required' => 'Deskripsi singkat wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'highlight_quote.required' => 'Highlight quote wajib diisi.',
            'gambar_utama.required' => 'Gambar utama wajib diunggah.',
            'gambar_utama.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar_utama.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar_utama.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
            'harga_tiket.required' => 'Harga tiket masuk wajib diisi.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka.',
            'harga_parkir_motor.required' => 'Harga parkir motor wajib diisi.',
            'harga_parkir_mobil.required' => 'Harga parkir mobil wajib diisi.',
            'jam_operasional.required' => 'Jam operasional wajib diisi.',
            'hari_buka.required' => 'Hari operasional wajib diisi.',
            'jarak_dari_desa.required' => 'Jarak dari desa wajib diisi.',
            'durasi_trek.required' => 'Durasi trekking wajib diisi.',
            'cocok_untuk.required' => 'Cocok untuk wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'google_maps_embed_url.required' => 'Google Maps embed URL wajib diisi.',
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
        $wisata->load(['galeriWisata', 'fasilitasWisata', 'tipsWisata', 'ruteWisata', 'ulasanWisata', 'penginapan']);
        $categories = KategoriWisata::all();
        $allPenginapan = \App\Models\Penginapan::all();

        return view('admin.wisata.edit', compact('wisata', 'categories', 'allPenginapan'));
    }

    public function update(Request $request, Wisata $wisata): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_wisata_id' => 'required|exists:kategori_wisata,id',
            'deskripsi_singkat' => 'required|string|max:1000',
            'deskripsi' => 'required|string',
            'highlight_quote' => 'required|string|max:500',
            'gambar_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'harga_tiket' => 'required|numeric|min:0',
            'harga_parkir_motor' => 'required|string|max:50',
            'harga_parkir_mobil' => 'required|string|max:50',
            'jam_operasional' => 'required|string|max:100',
            'hari_buka' => 'required|string|max:100',
            'jarak_dari_desa' => 'required|string|max:100',
            'durasi_trek' => 'required|string|max:100',
            'cocok_untuk' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'google_maps_embed_url' => 'required|string',
            'is_unggulan' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama destinasi wisata wajib diisi.',
            'kategori_wisata_id.required' => 'Kategori wisata wajib dipilih.',
            'kategori_wisata_id.exists' => 'Kategori wisata yang dipilih tidak valid.',
            'deskripsi_singkat.required' => 'Deskripsi singkat wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'highlight_quote.required' => 'Highlight quote wajib diisi.',
            'gambar_utama.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar_utama.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar_utama.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
            'harga_tiket.required' => 'Harga tiket masuk wajib diisi.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka.',
            'harga_parkir_motor.required' => 'Harga parkir motor wajib diisi.',
            'harga_parkir_mobil.required' => 'Harga parkir mobil wajib diisi.',
            'jam_operasional.required' => 'Jam operasional wajib diisi.',
            'hari_buka.required' => 'Hari operasional wajib diisi.',
            'jarak_dari_desa.required' => 'Jarak dari desa wajib diisi.',
            'durasi_trek.required' => 'Durasi trekking wajib diisi.',
            'cocok_untuk.required' => 'Cocok untuk wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'google_maps_embed_url.required' => 'Google Maps embed URL wajib diisi.',
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

    /**
     * Sinkronisasi penginapan / homestay yang terkait dengan destinasi wisata.
     */
    public function syncPenginapan(Request $request, Wisata $wisata): RedirectResponse
    {
        $wisata->penginapan()->sync($request->penginapan_ids ?? []);

        return redirect()->to(route('admin.wisata.edit', $wisata->id) . '#tab-penginapan')->with('success', 'Relasi penginapan / homestay berhasil diperbarui!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        $titleFields = ['nama', 'cocok_untuk'];
        foreach ($titleFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $request->merge([$field => $cleaned]);
            }
        }

        $stringFields = ['deskripsi_singkat', 'highlight_quote', 'hari_buka', 'jam_operasional', 'jarak_dari_desa', 'durasi_trek', 'telepon', 'google_maps_embed_url', 'harga_parkir_motor', 'harga_parkir_mobil'];
        foreach ($stringFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $request->merge([$field => $cleaned]);
            }
        }
    }
}

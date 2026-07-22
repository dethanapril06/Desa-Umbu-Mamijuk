<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penginapan;
use App\Models\Wisata;
use App\Rules\LandscapeImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PenginapanController extends Controller
{
    public function index(): View
    {
        $penginapans = Penginapan::with('wisata')->latest()->get();

        return view('admin.penginapan.index', compact('penginapans'));
    }

    public function create(): View
    {
        $allWisata = Wisata::all();

        return view('admin.penginapan.create', compact('allWisata'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama_penginapan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'kisaran_harga' => 'required|string|max:100',
            'jarak' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:50',
            'fasilitas_singkat' => 'required|string|max:255',
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'wisata_ids' => 'required|array',
            'wisata_ids.*' => 'exists:wisata,id',
        ], [
            'nama_penginapan.required' => 'Nama penginapan wajib diisi.',
            'jenis.required' => 'Jenis penginapan wajib dipilih.',
            'kisaran_harga.required' => 'Kisaran harga wajib diisi.',
            'jarak.required' => 'Jarak / keterangan lokasi wajib diisi.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'fasilitas_singkat.required' => 'Fasilitas singkat wajib diisi.',
            'foto.required' => 'Foto penginapan wajib diunggah.',
            'wisata_ids.required' => 'Destinasi wisata terdekat wajib dipilih minimal satu.',
            'foto.dimensions' => 'Resolusi foto penginapan terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto', 'wisata_ids']);
        $data['is_published'] = $request->has('is_published') ? (bool) $request->is_published : true;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/penginapan', 'public');
            $data['foto'] = $path;
        }

        $penginapan = Penginapan::create($data);
        $penginapan->wisata()->sync($request->wisata_ids ?? []);

        return redirect()->route('admin.penginapan.index')->with('success', 'Data penginapan / homestay berhasil ditambahkan!');
    }

    public function edit(Penginapan $penginapan): View
    {
        $penginapan->load('wisata');
        $allWisata = Wisata::all();

        return view('admin.penginapan.edit', compact('penginapan', 'allWisata'));
    }

    public function update(Request $request, Penginapan $penginapan): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama_penginapan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'kisaran_harga' => 'required|string|max:100',
            'jarak' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:50',
            'fasilitas_singkat' => 'required|string|max:255',
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'wisata_ids' => 'required|array',
            'wisata_ids.*' => 'exists:wisata,id',
        ], [
            'nama_penginapan.required' => 'Nama penginapan wajib diisi.',
            'jenis.required' => 'Jenis penginapan wajib dipilih.',
            'kisaran_harga.required' => 'Kisaran harga wajib diisi.',
            'jarak.required' => 'Jarak / keterangan lokasi wajib diisi.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'fasilitas_singkat.required' => 'Fasilitas singkat wajib diisi.',
            'wisata_ids.required' => 'Destinasi wisata terdekat wajib dipilih minimal satu.',
            'foto.dimensions' => 'Resolusi foto penginapan terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto', 'wisata_ids']);
        $data['is_published'] = $request->has('is_published') ? (bool) $request->is_published : false;

        if ($request->hasFile('foto')) {
            if ($penginapan->foto && Storage::disk('public')->exists($penginapan->foto)) {
                Storage::disk('public')->delete($penginapan->foto);
            }
            $path = $request->file('foto')->store('images/penginapan', 'public');
            $data['foto'] = $path;
        }

        $penginapan->update($data);
        $penginapan->wisata()->sync($request->wisata_ids ?? []);

        return redirect()->route('admin.penginapan.index')->with('success', 'Data penginapan / homestay berhasil diperbarui!');
    }

    public function destroy(Penginapan $penginapan): RedirectResponse
    {
        if ($penginapan->foto && Storage::disk('public')->exists($penginapan->foto)) {
            Storage::disk('public')->delete($penginapan->foto);
        }

        $penginapan->delete();

        return redirect()->route('admin.penginapan.index')->with('success', 'Data penginapan / homestay berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('nama_penginapan') && is_string($request->input('nama_penginapan')) && !empty($request->input('nama_penginapan'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('nama_penginapan')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['nama_penginapan' => $cleaned]);
        }

        $stringFields = ['kisaran_harga', 'jarak', 'no_telepon', 'fasilitas_singkat'];
        foreach ($stringFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                if ($field === 'kisaran_harga') {
                    $cleaned = \App\Models\Penginapan::formatHarga($cleaned);
                }
                $request->merge([$field => $cleaned]);
            }
        }
    }
}

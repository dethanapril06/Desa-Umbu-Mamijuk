<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KategoriBeritaController extends Controller
{
    public function index(): View
    {
        $kategoriList = KategoriBerita::orderBy('id', 'desc')->paginate(10);
        return view('admin.kategori-berita.index', compact('kategoriList'));
    }

    public function create(): View
    {
        return view('admin.kategori-berita.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:kategori_berita,nama',
            'icon' => 'nullable|string|max:100',
        ];

        $messages = [
            'nama.required' => 'Nama kategori berita wajib diisi.',
            'nama.unique' => 'Nama kategori berita tersebut sudah terdaftar di sistem.',
            'nama.max' => 'Nama kategori berita maksimal 255 karakter.',
        ];

        $request->validate($rules, $messages);

        KategoriBerita::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.kategori-berita.index')->with('success', 'Kategori berita berhasil ditambahkan!');
    }

    public function edit(KategoriBerita $kategoriBerita): View
    {
        return view('admin.kategori-berita.edit', compact('kategoriBerita'));
    }

    public function update(Request $request, KategoriBerita $kategoriBerita): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:kategori_berita,nama,' . $kategoriBerita->id,
            'icon' => 'nullable|string|max:100',
        ];

        $messages = [
            'nama.required' => 'Nama kategori berita wajib diisi.',
            'nama.unique' => 'Nama kategori berita tersebut sudah terdaftar di sistem.',
            'nama.max' => 'Nama kategori berita maksimal 255 karakter.',
        ];

        $request->validate($rules, $messages);

        $kategoriBerita->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.kategori-berita.index')->with('success', 'Kategori berita berhasil diperbarui!');
    }

    public function destroy(KategoriBerita $kategoriBerita): RedirectResponse
    {
        // Check if there are posts under this category
        if ($kategoriBerita->berita()->count() > 0) {
            return redirect()->route('admin.kategori-berita.index')->with('error', 'Tidak dapat menghapus kategori ini karena masih memiliki berita terhubung!');
        }

        $kategoriBerita->delete();
        return redirect()->route('admin.kategori-berita.index')->with('success', 'Kategori berita berhasil dihapus!');
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

        if ($request->has('icon') && is_string($request->input('icon'))) {
            $request->merge(['icon' => trim($request->input('icon'))]);
        }
    }
}

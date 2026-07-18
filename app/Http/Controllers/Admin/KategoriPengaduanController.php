<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KategoriPengaduanController extends Controller
{
    public function index(): View
    {
        $kategoriList = KategoriPengaduan::orderBy('id', 'desc')->paginate(10);
        return view('admin.kategori-pengaduan.index', compact('kategoriList'));
    }

    public function create(): View
    {
        return view('admin.kategori-pengaduan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_pengaduan,nama',
            'icon' => 'nullable|string|max:100',
        ]);

        KategoriPengaduan::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.kategori-pengaduan.index')->with('success', 'Kategori pengaduan berhasil ditambahkan!');
    }

    public function edit(KategoriPengaduan $kategoriPengaduan): View
    {
        return view('admin.kategori-pengaduan.edit', compact('kategoriPengaduan'));
    }

    public function update(Request $request, KategoriPengaduan $kategoriPengaduan): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_pengaduan,nama,' . $kategoriPengaduan->id,
            'icon' => 'nullable|string|max:100',
        ]);

        $kategoriPengaduan->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.kategori-pengaduan.index')->with('success', 'Kategori pengaduan berhasil diperbarui!');
    }

    public function destroy(KategoriPengaduan $kategoriPengaduan): RedirectResponse
    {
        if ($kategoriPengaduan->pengaduan()->count() > 0) {
            return redirect()->route('admin.kategori-pengaduan.index')->with('error', 'Tidak dapat menghapus kategori ini karena masih memiliki pengaduan terhubung!');
        }

        $kategoriPengaduan->delete();
        return redirect()->route('admin.kategori-pengaduan.index')->with('success', 'Kategori pengaduan berhasil dihapus!');
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

        if ($request->has('icon') && is_string($request->input('icon')) && !empty($request->input('icon'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('icon')));
            $request->merge(['icon' => $cleaned]);
        }
    }
}
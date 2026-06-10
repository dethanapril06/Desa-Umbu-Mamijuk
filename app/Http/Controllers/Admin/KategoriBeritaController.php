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
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_berita,nama',
            'icon' => 'nullable|string|max:100',
        ]);

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
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_berita,nama,' . $kategoriBerita->id,
            'icon' => 'nullable|string|max:100',
        ]);

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
}

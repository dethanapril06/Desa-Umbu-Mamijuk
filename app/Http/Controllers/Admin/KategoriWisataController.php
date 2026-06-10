<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KategoriWisataController extends Controller
{
    public function index(): View
    {
        $kategoriList = KategoriWisata::orderBy('id', 'desc')->paginate(10);
        return view('admin.kategori-wisata.index', compact('kategoriList'));
    }

    public function create(): View
    {
        return view('admin.kategori-wisata.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_wisata,nama',
            'icon' => 'nullable|string|max:100',
        ]);

        KategoriWisata::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.kategori-wisata.index')->with('success', 'Kategori wisata berhasil ditambahkan!');
    }

    public function edit(KategoriWisata $kategoriWisata): View
    {
        return view('admin.kategori-wisata.edit', compact('kategoriWisata'));
    }

    public function update(Request $request, KategoriWisata $kategoriWisata): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_wisata,nama,' . $kategoriWisata->id,
            'icon' => 'nullable|string|max:100',
        ]);

        $kategoriWisata->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.kategori-wisata.index')->with('success', 'Kategori wisata berhasil diperbarui!');
    }

    public function destroy(KategoriWisata $kategoriWisata): RedirectResponse
    {
        if ($kategoriWisata->wisata()->count() > 0) {
            return redirect()->route('admin.kategori-wisata.index')->with('error', 'Tidak dapat menghapus kategori ini karena masih memiliki destinasi wisata terhubung!');
        }

        $kategoriWisata->delete();
        return redirect()->route('admin.kategori-wisata.index')->with('success', 'Kategori wisata berhasil dihapus!');
    }
}

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
}
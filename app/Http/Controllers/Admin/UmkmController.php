<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UmkmController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = Umkm::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $umkmList = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.umkm.index', compact('umkmList', 'search'));
    }

    public function create(): View
    {
        return view('admin.umkm.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website_url' => 'nullable|url|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=300,min_height=300',
            'jam_operasional' => 'nullable|string|max:150',
            'status' => 'required|in:aktif,tidak_aktif',
        ], [
            'foto.dimensions' => 'Resolusi foto terlalu kecil! Minimal lebar 300px dan tinggi 300px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $slug = Str::slug($request->nama_usaha);
        
        // Ensure unique slug
        $count = Umkm::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $data = $request->except(['foto']);
        $data['slug'] = $slug;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('umkm', 'public');
            $data['foto'] = $path;
        }

        Umkm::create($data);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM baru berhasil ditambahkan!');
    }

    public function edit(Umkm $umkm): View
    {
        return view('admin.umkm.edit', compact('umkm'));
    }

    public function update(Request $request, Umkm $umkm): RedirectResponse
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website_url' => 'nullable|url|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=300,min_height=300',
            'jam_operasional' => 'nullable|string|max:150',
            'status' => 'required|in:aktif,tidak_aktif',
        ], [
            'foto.dimensions' => 'Resolusi foto terlalu kecil! Minimal lebar 300px dan tinggi 300px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);

        if ($request->nama_usaha !== $umkm->nama_usaha) {
            $slug = Str::slug($request->nama_usaha);
            $count = Umkm::where('slug', 'like', "{$slug}%")->where('id', '!=', $umkm->id)->count();
            if ($count > 0) {
                $slug = "{$slug}-" . ($count + 1);
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('foto')) {
            if ($umkm->foto && Storage::disk('public')->exists($umkm->foto)) {
                Storage::disk('public')->delete($umkm->foto);
            }
            $path = $request->file('foto')->store('umkm', 'public');
            $data['foto'] = $path;
        }

        $umkm->update($data);

        return redirect()->route('admin.umkm.index')->with('success', 'Data UMKM berhasil diperbarui!');
    }

    public function destroy(Umkm $umkm): RedirectResponse
    {
        if ($umkm->foto && Storage::disk('public')->exists($umkm->foto)) {
            Storage::disk('public')->delete($umkm->foto);
        }
        
        $umkm->delete();

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil dihapus!');
    }
}

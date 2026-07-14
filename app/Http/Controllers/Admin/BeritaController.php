<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Rules\LandscapeImage;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori_id');

        $query = Berita::with(['kategoriBerita', 'user']);

        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
        }

        if ($kategori) {
            $query->where('kategori_berita_id', $kategori);
        }

        $beritaList = $query->orderBy('id', 'desc')->paginate(10);
        $categories = KategoriBerita::all();

        return view('admin.berita.index', compact('beritaList', 'categories', 'search', 'kategori'));
    }

    public function create(): View
    {
        $categories = KategoriBerita::all();
        $tags = Tag::all();
        return view('admin.berita.create', compact('categories', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
            'konten' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption_gambar' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar', 'tags']);
        
        $data['user_id'] = auth()->id() ?? 1; // Default to user 1 if not logged in (e.g. testing)
        $data['slug'] = Str::slug($request->judul) . '-' . time(); // Avoid duplicate slug issues
        $data['is_published'] = $request->has('is_published') ? (bool) $request->is_published : false;
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images/berita', 'public');
            $data['gambar'] = $path;
        }

        $berita = Berita::create($data);

        if ($request->has('tags')) {
            $berita->tags()->sync($request->tags);
        }

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function edit(Berita $berita): View
    {
        $categories = KategoriBerita::all();
        $tags = Tag::all();
        $beritaTagIds = $berita->tags->pluck('id')->toArray();

        return view('admin.berita.edit', compact('berita', 'categories', 'tags', 'beritaTagIds'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
            'konten' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption_gambar' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar', 'tags']);
        $data['is_published'] = $request->has('is_published') ? (bool) $request->is_published : false;

        // Manage published_at
        if ($data['is_published']) {
            if (!$berita->is_published) {
                $data['published_at'] = now();
            }
        } else {
            $data['published_at'] = null;
        }

        // Regenerate slug if title changes
        if ($request->judul !== $berita->judul) {
            $data['slug'] = Str::slug($request->judul) . '-' . time();
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $path = $request->file('gambar')->store('images/berita', 'public');
            $data['gambar'] = $path;
        }

        $berita->update($data);

        $tagsToSync = $request->has('tags') ? $request->tags : [];
        $berita->tags()->sync($tagsToSync);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }
        $berita->tags()->detach();
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}

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
        $this->normalizeInput($request);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
            'konten' => 'required|string',
            'excerpt' => 'required|string|max:500',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption_gambar' => 'required|string|max:255',
            'is_published' => 'nullable|boolean',
            'tags' => 'required|array|min:1',
            'tags.*' => 'exists:tags,id',
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'kategori_berita_id.required' => 'Kategori berita wajib dipilih.',
            'kategori_berita_id.exists' => 'Kategori berita yang dipilih tidak valid.',
            'konten.required' => 'Konten berita wajib diisi.',
            'excerpt.required' => 'Ringkasan singkat wajib diisi.',
            'gambar.required' => 'Gambar cover wajib diunggah.',
            'caption_gambar.required' => 'Caption gambar wajib diisi.',
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
            'tags.required' => 'Tag berita wajib dipilih minimal satu.',
            'tags.min' => 'Tag berita wajib dipilih minimal satu.',
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
        $this->normalizeInput($request);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
            'konten' => 'required|string',
            'excerpt' => 'required|string|max:500',
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=400,min_height=250', new LandscapeImage],
            'caption_gambar' => 'required|string|max:255',
            'is_published' => 'nullable|boolean',
            'tags' => 'required|array|min:1',
            'tags.*' => 'exists:tags,id',
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'kategori_berita_id.required' => 'Kategori berita wajib dipilih.',
            'kategori_berita_id.exists' => 'Kategori berita yang dipilih tidak valid.',
            'konten.required' => 'Konten berita wajib diisi.',
            'excerpt.required' => 'Ringkasan singkat wajib diisi.',
            'caption_gambar.required' => 'Caption gambar wajib diisi.',
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 400px dan tinggi 250px.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
            'tags.required' => 'Tag berita wajib dipilih minimal satu.',
            'tags.min' => 'Tag berita wajib dipilih minimal satu.',
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

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        $titleFields = ['judul', 'caption_gambar'];
        foreach ($titleFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $request->merge([$field => $cleaned]);
            }
        }

        if ($request->has('excerpt') && is_string($request->input('excerpt')) && !empty($request->input('excerpt'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('excerpt')));
            $request->merge(['excerpt' => $cleaned]);
        }
    }
}

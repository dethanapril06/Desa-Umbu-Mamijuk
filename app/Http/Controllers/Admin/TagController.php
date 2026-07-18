<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::orderBy('id', 'desc')->paginate(15);
        return view('admin.tag.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.tag.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:tags,nama',
        ];

        $messages = [
            'nama.required' => 'Nama tag berita wajib diisi.',
            'nama.unique' => 'Nama tag berita tersebut sudah terdaftar di sistem.',
            'nama.max' => 'Nama tag berita maksimal 255 karakter.',
        ];

        $request->validate($rules, $messages);

        Tag::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
        ]);

        return redirect()->route('admin.tag.index')->with('success', 'Tag berita berhasil ditambahkan!');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tag.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:tags,nama,' . $tag->id,
        ];

        $messages = [
            'nama.required' => 'Nama tag berita wajib diisi.',
            'nama.unique' => 'Nama tag berita tersebut sudah terdaftar di sistem.',
            'nama.max' => 'Nama tag berita maksimal 255 karakter.',
        ];

        $request->validate($rules, $messages);

        $tag->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
        ]);

        return redirect()->route('admin.tag.index')->with('success', 'Tag berita berhasil diperbarui!');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        // Many-to-many relationship handles deletion gracefully or we can just detach
        $tag->berita()->detach();
        $tag->delete();

        return redirect()->route('admin.tag.index')->with('success', 'Tag berita berhasil dihapus!');
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
    }
}

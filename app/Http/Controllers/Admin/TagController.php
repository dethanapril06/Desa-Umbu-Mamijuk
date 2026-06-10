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
        $request->validate([
            'nama' => 'required|string|max:255|unique:tags,nama',
        ]);

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
        $request->validate([
            'nama' => 'required|string|max:255|unique:tags,nama,' . $tag->id,
        ]);

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
}

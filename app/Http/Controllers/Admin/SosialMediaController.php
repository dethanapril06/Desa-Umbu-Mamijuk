<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SosialMedia;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SosialMediaController extends Controller
{
    public function index(): View
    {
        $socials = SosialMedia::orderBy('id', 'asc')->paginate(15);
        return view('admin.sosial-media.index', compact('socials'));
    }

    public function create(): View
    {
        return view('admin.sosial-media.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url|max:255',
            'icon' => 'required|string|max:100', // e.g. 'bx-facebook' or 'bxl-facebook' or fontawesome
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        SosialMedia::create($data);

        return redirect()->route('admin.sosial-media.index')->with('success', 'Sosial media baru berhasil ditambahkan!');
    }

    public function edit(SosialMedia $sosialMedia): View
    {
        return view('admin.sosial-media.edit', compact('sosialMedia'));
    }

    public function update(Request $request, SosialMedia $sosialMedia): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url|max:255',
            'icon' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $sosialMedia->update($data);

        return redirect()->route('admin.sosial-media.index')->with('success', 'Sosial media berhasil diperbarui!');
    }

    public function destroy(SosialMedia $sosialMedia): RedirectResponse
    {
        $sosialMedia->delete();
        return redirect()->route('admin.sosial-media.index')->with('success', 'Sosial media berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('platform') && is_string($request->input('platform')) && !empty($request->input('platform'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('platform')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['platform' => $cleaned]);
        }

        $stringFields = ['url', 'icon'];
        foreach ($stringFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $request->merge([$field => $cleaned]);
            }
        }
    }
}

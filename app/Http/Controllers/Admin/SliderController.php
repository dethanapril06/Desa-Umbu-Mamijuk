<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Rules\LandscapeImage;

class SliderController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::orderBy('urutan', 'asc')->paginate(10);
        return view('admin.slider.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('admin.slider.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'urutan' => 'required|integer|min:1',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=800,min_height=350', new LandscapeImage],
            'is_active' => 'nullable|boolean',
        ], [
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 800px dan tinggi 350px agar tampilan banner tidak pecah.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images/slider', 'public');
            $data['gambar'] = $path;
        }

        Slider::create($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slider baru berhasil ditambahkan!');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'urutan' => 'required|integer|min:1',
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=800,min_height=350', new LandscapeImage],
            'is_active' => 'nullable|boolean',
        ], [
            'gambar.dimensions' => 'Resolusi gambar terlalu kecil! Minimal lebar 800px dan tinggi 350px agar tampilan banner tidak pecah.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['gambar']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('gambar')) {
            if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $path = $request->file('gambar')->store('images/slider', 'public');
            $data['gambar'] = $path;
        }

        $slider->update($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slider berhasil diperbarui!');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
            Storage::disk('public')->delete($slider->gambar);
        }
        $slider->delete();

        return redirect()->route('admin.slider.index')->with('success', 'Slider berhasil dihapus!');
    }
}

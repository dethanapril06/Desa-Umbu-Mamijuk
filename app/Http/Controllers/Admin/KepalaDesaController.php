<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KepalaDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KepalaDesaController extends Controller
{
    public function index(): View
    {
        $kepalaDesaList = KepalaDesa::orderBy('id', 'desc')->paginate(10);
        return view('admin.kepala-desa.index', compact('kepalaDesaList'));
    }

    public function create(): View
    {
        return view('admin.kepala-desa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
            'periode_mulai' => 'required|string|max:50',
            'periode_selesai' => 'required|string|max:50',
            'sambutan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/kepala-desa', 'public');
            $data['foto'] = $path;
        }

        if ($data['is_active']) {
            // Deactivate all others
            KepalaDesa::query()->update(['is_active' => false]);
        }

        KepalaDesa::create($data);

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Data kepala desa berhasil ditambahkan!');
    }

    public function edit(KepalaDesa $kepalaDesa): View
    {
        return view('admin.kepala-desa.edit', compact('kepalaDesa'));
    }

    public function update(Request $request, KepalaDesa $kepalaDesa): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
            'periode_mulai' => 'required|string|max:50',
            'periode_selesai' => 'required|string|max:50',
            'sambutan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            if ($kepalaDesa->foto && Storage::disk('public')->exists($kepalaDesa->foto)) {
                Storage::disk('public')->delete($kepalaDesa->foto);
            }
            $path = $request->file('foto')->store('images/kepala-desa', 'public');
            $data['foto'] = $path;
        }

        if ($data['is_active']) {
            // Deactivate all others
            KepalaDesa::where('id', '!=', $kepalaDesa->id)->update(['is_active' => false]);
        }

        $kepalaDesa->update($data);

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Data kepala desa berhasil diperbarui!');
    }

    public function destroy(KepalaDesa $kepalaDesa): RedirectResponse
    {
        if ($kepalaDesa->foto && Storage::disk('public')->exists($kepalaDesa->foto)) {
            Storage::disk('public')->delete($kepalaDesa->foto);
        }
        $kepalaDesa->delete();

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Data kepala desa berhasil dihapus!');
    }

    /**
     * Tampilkan detail atau status kepala desa
     */
    public function toggleStatus(KepalaDesa $kepalaDesa): RedirectResponse
    {
        $newStatus = !$kepalaDesa->is_active;

        if ($newStatus) {
            KepalaDesa::query()->update(['is_active' => false]);
        }

        $kepalaDesa->update(['is_active' => $newStatus]);

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Status keaktifan kepala desa berhasil diubah!');
    }
}

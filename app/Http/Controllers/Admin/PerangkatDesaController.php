<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PerangkatDesaController extends Controller
{
    public function index(): View
    {
        $perangkatDesaList = PerangkatDesa::orderBy('urutan', 'asc')->paginate(15);
        return view('admin.perangkat-desa.index', compact('perangkatDesaList'));
    }

    public function create(): View
    {
        return view('admin.perangkat-desa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'urutan' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/perangkat-desa', 'public');
            $data['foto'] = $path;
        }

        PerangkatDesa::create($data);

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil ditambahkan!');
    }

    public function edit(PerangkatDesa $perangkatDesa): View
    {
        return view('admin.perangkat-desa.edit', compact('perangkatDesa'));
    }

    public function update(Request $request, PerangkatDesa $perangkatDesa): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'urutan' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
                Storage::disk('public')->delete($perangkatDesa->foto);
            }
            $path = $request->file('foto')->store('images/perangkat-desa', 'public');
            $data['foto'] = $path;
        }

        $perangkatDesa->update($data);

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil diperbarui!');
    }

    public function destroy(PerangkatDesa $perangkatDesa): RedirectResponse
    {
        if ($perangkatDesa->foto && Storage::disk('public')->exists($perangkatDesa->foto)) {
            Storage::disk('public')->delete($perangkatDesa->foto);
        }
        $perangkatDesa->delete();

        return redirect()->route('admin.perangkat-desa.index')->with('success', 'Perangkat desa berhasil dihapus!');
    }
}

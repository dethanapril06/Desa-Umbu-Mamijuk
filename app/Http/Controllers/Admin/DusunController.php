<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DusunController extends Controller
{
    public function index(): View
    {
        $dusunList = Dusun::orderBy('urutan', 'asc')->paginate(10);
        return view('admin.dusun.index', compact('dusunList'));
    }

    public function create(): View
    {
        return view('admin.dusun.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:dusun,nama',
            'kepala_dusun' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        Dusun::create($data);

        return redirect()->route('admin.dusun.index')->with('success', 'Data dusun berhasil ditambahkan!');
    }

    public function edit(Dusun $dusun): View
    {
        return view('admin.dusun.edit', compact('dusun'));
    }

    public function update(Request $request, Dusun $dusun): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:dusun,nama,' . $dusun->id,
            'kepala_dusun' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $dusun->update($data);

        return redirect()->route('admin.dusun.index')->with('success', 'Data dusun berhasil diperbarui!');
    }

    public function destroy(Dusun $dusun): RedirectResponse
    {
        // Check if there are RT/RW under this dusun
        if ($dusun->rtRw()->count() > 0) {
            return redirect()->route('admin.dusun.index')->with('error', 'Tidak dapat menghapus dusun ini karena memiliki data RT/RW!');
        }

        $dusun->delete();
        return redirect()->route('admin.dusun.index')->with('success', 'Data dusun berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RtRw;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RtRwController extends Controller
{
    public function index(): View
    {
        $rtRwList = RtRw::with('dusun')->orderBy('id', 'desc')->paginate(15);
        return view('admin.rt-rw.index', compact('rtRwList'));
    }

    public function create(): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('urutan', 'asc')->get();
        return view('admin.rt-rw.create', compact('dusunList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dusun_id' => 'required|exists:dusun,id',
            'no_rt' => 'required|string|max:10',
            'no_rw' => 'required|string|max:10',
            'ketua_rt' => 'nullable|string|max:255',
            'ketua_rw' => 'nullable|string|max:255',
        ]);

        // Validate uniqueness of RT in the same RW and Dusun
        $exists = RtRw::where('dusun_id', $request->dusun_id)
            ->where('no_rt', $request->no_rt)
            ->where('no_rw', $request->no_rw)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['no_rt' => 'Kombinasi RT, RW, dan Dusun tersebut sudah terdaftar!']);
        }

        RtRw::create($request->all());

        return redirect()->route('admin.rt-rw.index')->with('success', 'Data RT/RW berhasil ditambahkan!');
    }

    public function edit(RtRw $rtRw): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('urutan', 'asc')->get();
        return view('admin.rt-rw.edit', compact('rtRw', 'dusunList'));
    }

    public function update(Request $request, RtRw $rtRw): RedirectResponse
    {
        $request->validate([
            'dusun_id' => 'required|exists:dusun,id',
            'no_rt' => 'required|string|max:10',
            'no_rw' => 'required|string|max:10',
            'ketua_rt' => 'nullable|string|max:255',
            'ketua_rw' => 'nullable|string|max:255',
        ]);

        // Validate uniqueness excluding current id
        $exists = RtRw::where('dusun_id', $request->dusun_id)
            ->where('no_rt', $request->no_rt)
            ->where('no_rw', $request->no_rw)
            ->where('id', '!=', $rtRw->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['no_rt' => 'Kombinasi RT, RW, dan Dusun tersebut sudah terdaftar!']);
        }

        $rtRw->update($request->all());

        return redirect()->route('admin.rt-rw.index')->with('success', 'Data RT/RW berhasil diperbarui!');
    }

    public function destroy(RtRw $rtRw): RedirectResponse
    {
        // Check if there are keluarga under this RT/RW
        if ($rtRw->keluarga()->count() > 0) {
            return redirect()->route('admin.rt-rw.index')->with('error', 'Tidak dapat menghapus data RT/RW ini karena memiliki data Kartu Keluarga terhubung!');
        }

        $rtRw->delete();
        return redirect()->route('admin.rt-rw.index')->with('success', 'Data RT/RW berhasil dihapus!');
    }
}

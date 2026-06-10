<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\RtRw;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KeluargaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = Keluarga::with(['rtRw.dusun', 'penduduk']);

        if ($search) {
            $query->where('no_kk', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhereHas('penduduk', function ($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->where('status_hubungan_keluarga', 'kepala_keluarga');
                  });
        }

        $keluargaList = $query->orderBy('id', 'desc')->paginate(15);
        return view('admin.keluarga.index', compact('keluargaList', 'search'));
    }

    public function create(): View
    {
        $rtRwList = RtRw::with('dusun')->get();
        return view('admin.keluarga.create', compact('rtRwList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:keluarga,no_kk',
            'rt_rw_id' => 'required|exists:rt_rw,id',
            'alamat' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_terdaftar' => 'nullable|date',
        ]);

        Keluarga::create($request->all());

        return redirect()->route('admin.keluarga.index')->with('success', 'Kartu Keluarga berhasil ditambahkan!');
    }

    public function show(Keluarga $keluarga): View
    {
        $keluarga->load(['rtRw.dusun', 'penduduk' => function ($query) {
            $query->orderByRaw("FIELD(status_hubungan_keluarga, 'kepala_keluarga', 'istri', 'anak', 'menantu', 'cucu', 'orang_tua', 'mertua', 'famili_lain', 'lainnya')");
        }]);

        $kepalaKeluarga = $keluarga->penduduk->where('status_hubungan_keluarga', 'kepala_keluarga')->first();

        return view('admin.keluarga.show', compact('keluarga', 'kepalaKeluarga'));
    }

    public function edit(Keluarga $keluarga): View
    {
        $rtRwList = RtRw::with('dusun')->get();
        return view('admin.keluarga.edit', compact('keluarga', 'rtRwList'));
    }

    public function update(Request $request, Keluarga $keluarga): RedirectResponse
    {
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:keluarga,no_kk,' . $keluarga->id,
            'rt_rw_id' => 'required|exists:rt_rw,id',
            'alamat' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_terdaftar' => 'nullable|date',
        ]);

        $keluarga->update($request->all());

        return redirect()->route('admin.keluarga.index')->with('success', 'Kartu Keluarga berhasil diperbarui!');
    }

    public function destroy(Keluarga $keluarga): RedirectResponse
    {
        // Check if there are members in this keluarga
        if ($keluarga->penduduk()->count() > 0) {
            return redirect()->route('admin.keluarga.index')->with('error', 'Tidak dapat menghapus KK ini karena memiliki anggota keluarga terdaftar! Silakan hapus atau pindahkan anggota keluarga terlebih dahulu.');
        }

        $keluarga->delete();
        return redirect()->route('admin.keluarga.index')->with('success', 'Kartu Keluarga berhasil dihapus!');
    }
}

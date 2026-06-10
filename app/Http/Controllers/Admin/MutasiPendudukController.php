<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MutasiPendudukController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis_mutasi');

        $query = MutasiPenduduk::with('penduduk');

        if ($search) {
            $query->whereHas('penduduk', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })->orWhere('no_surat', 'like', "%{$search}%");
        }

        if ($jenis) {
            $query->where('jenis_mutasi', $jenis);
        }

        $mutasiList = $query->orderBy('tanggal_mutasi', 'desc')->paginate(15);
        return view('admin.mutasi.index', compact('mutasiList', 'search', 'jenis'));
    }

    public function create(): View
    {
        // Only fetch active residents for recording mutations (except maybe babies/born ones who are already entered in Penduduk)
        $pendudukList = Penduduk::where('status', 'aktif')->orderBy('nama_lengkap', 'asc')->get();
        return view('admin.mutasi.create', compact('pendudukList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id',
            'jenis_mutasi' => 'required|in:lahir,mati,pindah_masuk,pindah_keluar',
            'tanggal_mutasi' => 'required|date',
            'no_surat' => 'nullable|string|max:100',
            'alamat_tujuan' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['lampiran']);

        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        $mutasi = MutasiPenduduk::create($data);

        // Auto update Penduduk status
        $penduduk = Penduduk::find($request->penduduk_id);
        if ($request->jenis_mutasi === 'mati') {
            $penduduk->status = 'meninggal';
        } elseif ($request->jenis_mutasi === 'pindah_keluar') {
            $penduduk->status = 'pindah';
        } elseif ($request->jenis_mutasi === 'pindah_masuk' || $request->jenis_mutasi === 'lahir') {
            $penduduk->status = 'aktif';
        }
        $penduduk->save();

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil ditambahkan!');
    }

    public function edit(MutasiPenduduk $mutasiPenduduk): View
    {
        $pendudukList = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.mutasi.edit', compact('mutasiPenduduk', 'pendudukList'));
    }

    public function update(Request $request, MutasiPenduduk $mutasiPenduduk): RedirectResponse
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id',
            'jenis_mutasi' => 'required|in:lahir,mati,pindah_masuk,pindah_keluar',
            'tanggal_mutasi' => 'required|date',
            'no_surat' => 'nullable|string|max:100',
            'alamat_tujuan' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        // Restore original resident's status before saving new ones if penduduk_id or jenis_mutasi changes
        $oldPenduduk = $mutasiPenduduk->penduduk;
        $oldPenduduk->status = 'aktif';
        $oldPenduduk->save();

        $data = $request->except(['lampiran']);

        if ($request->hasFile('lampiran')) {
            if ($mutasiPenduduk->lampiran && Storage::disk('public')->exists($mutasiPenduduk->lampiran)) {
                Storage::disk('public')->delete($mutasiPenduduk->lampiran);
            }
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        $mutasiPenduduk->update($data);

        // Apply new status to the new/updated resident
        $newPenduduk = Penduduk::find($request->penduduk_id);
        if ($request->jenis_mutasi === 'mati') {
            $newPenduduk->status = 'meninggal';
        } elseif ($request->jenis_mutasi === 'pindah_keluar') {
            $newPenduduk->status = 'pindah';
        } elseif ($request->jenis_mutasi === 'pindah_masuk' || $request->jenis_mutasi === 'lahir') {
            $newPenduduk->status = 'aktif';
        }
        $newPenduduk->save();

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil diperbarui!');
    }

    public function destroy(MutasiPenduduk $mutasiPenduduk): RedirectResponse
    {
        // Restore status to aktif
        $penduduk = $mutasiPenduduk->penduduk;
        if ($penduduk) {
            $penduduk->status = 'aktif';
            $penduduk->save();
        }

        if ($mutasiPenduduk->lampiran && Storage::disk('public')->exists($mutasiPenduduk->lampiran)) {
            Storage::disk('public')->delete($mutasiPenduduk->lampiran);
        }

        $mutasiPenduduk->delete();

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil dihapus, status penduduk dikembalikan menjadi aktif!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
        // Only fetch active residents for recording mutations
        $pendudukList = Penduduk::where('status', 'aktif')->orderBy('nama_lengkap', 'asc')->get();
        $keluargaList = Keluarga::with(['kepalaKeluarga', 'istri'])->orderBy('no_kk', 'asc')->get();
        return view('admin.mutasi.create', compact('pendudukList', 'keluargaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $isLahir = $request->input('jenis_mutasi') === 'lahir';

        $rules = [
            'jenis_mutasi' => 'required|in:lahir,mati,pindah_masuk,pindah_keluar',
            'tanggal_mutasi' => 'required|date',
            'no_surat' => 'nullable|string|max:100',
            'alamat_tujuan' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        if ($isLahir) {
            $rules['baby_nama_lengkap'] = 'required|string|max:255';
            $rules['baby_nik'] = 'required|string|size:16|unique:penduduk,nik';
            $rules['baby_tempat_lahir'] = 'nullable|string|max:100';
            $rules['baby_tanggal_lahir'] = 'required|date';
            $rules['baby_jenis_kelamin'] = 'required|in:laki-laki,perempuan';
            $rules['baby_keluarga_id'] = 'required|exists:keluarga,id';
            $rules['baby_nama_ayah'] = 'nullable|string|max:255';
            $rules['baby_nama_ibu'] = 'nullable|string|max:255';
        } else {
            $rules['penduduk_id'] = 'required|exists:penduduk,id';
        }

        $request->validate($rules);

        $data = $request->except(['lampiran']);

        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        if ($isLahir) {
            DB::transaction(function () use ($request, $data) {
                // Create newborn resident
                $baby = Penduduk::create([
                    'keluarga_id' => $request->baby_keluarga_id,
                    'nik' => $request->baby_nik,
                    'nama_lengkap' => $request->baby_nama_lengkap,
                    'tempat_lahir' => $request->baby_tempat_lahir,
                    'tanggal_lahir' => $request->baby_tanggal_lahir,
                    'jenis_kelamin' => $request->baby_jenis_kelamin,
                    'agama' => 'islam', // Default
                    'pendidikan_terakhir' => 'tidak_sekolah',
                    'status_perkawinan' => 'belum_kawin',
                    'status_hubungan_keluarga' => 'anak',
                    'kewarganegaraan' => 'WNI',
                    'nama_ayah' => $request->baby_nama_ayah,
                    'nama_ibu' => $request->baby_nama_ibu,
                    'status' => 'aktif',
                ]);

                // Record the mutation linking to newborn
                $data['penduduk_id'] = $baby->id;
                MutasiPenduduk::create($data);
            });
        } else {
            DB::transaction(function () use ($request, $data) {
                MutasiPenduduk::create($data);

                // Auto update Penduduk status
                $penduduk = Penduduk::find($request->penduduk_id);
                if ($request->jenis_mutasi === 'mati') {
                    $penduduk->status = 'meninggal';
                } elseif ($request->jenis_mutasi === 'pindah_keluar') {
                    $penduduk->status = 'pindah';
                } elseif ($request->jenis_mutasi === 'pindah_masuk') {
                    $penduduk->status = 'aktif';
                }
                $penduduk->save();
            });
        }

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil ditambahkan!');
    }

    public function edit(MutasiPenduduk $mutasiPenduduk): View
    {
        $pendudukList = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        $keluargaList = Keluarga::with(['kepalaKeluarga', 'istri'])->orderBy('no_kk', 'asc')->get();
        return view('admin.mutasi.edit', compact('mutasiPenduduk', 'pendudukList', 'keluargaList'));
    }

    public function update(Request $request, MutasiPenduduk $mutasiPenduduk): RedirectResponse
    {
        $isLahir = $request->input('jenis_mutasi') === 'lahir';

        $rules = [
            'jenis_mutasi' => 'required|in:lahir,mati,pindah_masuk,pindah_keluar',
            'tanggal_mutasi' => 'required|date',
            'no_surat' => 'nullable|string|max:100',
            'alamat_tujuan' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        if ($isLahir) {
            $rules['baby_nama_lengkap'] = 'required|string|max:255';
            $rules['baby_nik'] = 'required|string|size:16|unique:penduduk,nik,' . $mutasiPenduduk->penduduk_id;
            $rules['baby_tempat_lahir'] = 'nullable|string|max:100';
            $rules['baby_tanggal_lahir'] = 'required|date';
            $rules['baby_jenis_kelamin'] = 'required|in:laki-laki,perempuan';
            $rules['baby_keluarga_id'] = 'required|exists:keluarga,id';
            $rules['baby_nama_ayah'] = 'nullable|string|max:255';
            $rules['baby_nama_ibu'] = 'nullable|string|max:255';
        } else {
            $rules['penduduk_id'] = 'required|exists:penduduk,id';
        }

        $request->validate($rules);

        $data = $request->except(['lampiran']);

        if ($request->hasFile('lampiran')) {
            if ($mutasiPenduduk->lampiran && Storage::disk('public')->exists($mutasiPenduduk->lampiran)) {
                Storage::disk('public')->delete($mutasiPenduduk->lampiran);
            }
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        DB::transaction(function () use ($request, $mutasiPenduduk, $data, $isLahir) {
            if ($isLahir) {
                // Update newborn resident
                $baby = $mutasiPenduduk->penduduk;
                if ($baby) {
                    $baby->update([
                        'keluarga_id' => $request->baby_keluarga_id,
                        'nik' => $request->baby_nik,
                        'nama_lengkap' => $request->baby_nama_lengkap,
                        'tempat_lahir' => $request->baby_tempat_lahir,
                        'tanggal_lahir' => $request->baby_tanggal_lahir,
                        'jenis_kelamin' => $request->baby_jenis_kelamin,
                        'nama_ayah' => $request->baby_nama_ayah,
                        'nama_ibu' => $request->baby_nama_ibu,
                    ]);
                    $data['penduduk_id'] = $baby->id;
                }
                $mutasiPenduduk->update($data);
            } else {
                // Restore original resident's status before saving new ones if they change
                $oldPenduduk = $mutasiPenduduk->penduduk;
                if ($oldPenduduk) {
                    $oldPenduduk->status = 'aktif';
                    $oldPenduduk->save();
                }

                $mutasiPenduduk->update($data);

                // Apply new status to the new/updated resident
                $newPenduduk = Penduduk::find($request->penduduk_id);
                if ($request->jenis_mutasi === 'mati') {
                    $newPenduduk->status = 'meninggal';
                } elseif ($request->jenis_mutasi === 'pindah_keluar') {
                    $newPenduduk->status = 'pindah';
                } elseif ($request->jenis_mutasi === 'pindah_masuk') {
                    $newPenduduk->status = 'aktif';
                }
                $newPenduduk->save();
            }
        });

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil diperbarui!');
    }

    public function destroy(MutasiPenduduk $mutasiPenduduk): RedirectResponse
    {
        // Restore status to aktif
        $penduduk = $mutasiPenduduk->penduduk;
        if ($penduduk) {
            // Note: If type was birth (lahir), deleting the mutation record should we delete the baby resident record too?
            // Usually, deleting the birth mutation event doesn't mean deleting the person.
            // But we will restore status to aktif anyway.
            $penduduk->status = 'aktif';
            $penduduk->save();
        }

        if ($mutasiPenduduk->lampiran && Storage::disk('public')->exists($mutasiPenduduk->lampiran)) {
            Storage::disk('public')->delete($mutasiPenduduk->lampiran);
        }

        $mutasiPenduduk->delete();

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil dihapus!');
    }
}

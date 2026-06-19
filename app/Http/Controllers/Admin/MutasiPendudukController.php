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
        $isMasuk = $request->input('jenis_mutasi') === 'pindah_masuk';

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
        } elseif ($isMasuk) {
            $rules['masuk_keluarga_id'] = 'required|exists:keluarga,id';
            $rules['masuk_nik'] = 'required|string|size:16|unique:penduduk,nik';
            $rules['masuk_nama_lengkap'] = 'required|string|max:255';
            $rules['masuk_tempat_lahir'] = 'nullable|string|max:100';
            $rules['masuk_tanggal_lahir'] = 'required|date';
            $rules['masuk_jenis_kelamin'] = 'required|in:laki-laki,perempuan';
            $rules['masuk_agama'] = 'nullable|in:islam,kristen,katolik,hindu,buddha,konghucu,lainnya';
            $rules['masuk_pendidikan_terakhir'] = 'nullable|in:tidak_sekolah,sd,smp,sma,d1,d2,d3,s1,s2,s3';
            $rules['masuk_pekerjaan'] = 'nullable|string|max:100';
            $rules['masuk_status_perkawinan'] = 'nullable|in:belum_kawin,kawin,cerai_hidup,cerai_mati';
            $rules['masuk_status_hubungan_keluarga'] = 'nullable|in:kepala_keluarga,istri,anak,menantu,cucu,orang_tua,mertua,famili_lain,lainnya';
            $rules['masuk_kewarganegaraan'] = 'required|in:WNI,WNA';
            $rules['masuk_golongan_darah'] = 'nullable|string|max:5';
            $rules['masuk_no_paspor'] = 'nullable|string|max:50';
            $rules['masuk_no_kitas_kitap'] = 'nullable|string|max:50';
            $rules['masuk_nama_ayah'] = 'nullable|string|max:255';
            $rules['masuk_nama_ibu'] = 'nullable|string|max:255';
            $rules['masuk_no_telepon'] = 'nullable|string|max:20';
            $rules['masuk_is_asuransi_kesehatan'] = 'nullable|boolean';
            $rules['masuk_is_disabilitas'] = 'nullable|boolean';
            $rules['masuk_jenis_disabilitas'] = 'nullable|string|max:255';
        } else {
            $rules['penduduk_id'] = 'required|exists:penduduk,id';
        }

        $request->validate($rules);

        $data = $request->except(['lampiran']);

        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        $promotedMessage = '';

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
        } elseif ($isMasuk) {
            DB::transaction(function () use ($request, $data) {
                $isAsuransi = $request->has('masuk_is_asuransi_kesehatan') ? (bool) $request->masuk_is_asuransi_kesehatan : false;
                $isDisabilitas = $request->has('masuk_is_disabilitas') ? (bool) $request->masuk_is_disabilitas : false;

                // If status_hubungan_keluarga is kepala_keluarga, demote the existing one in the KK
                if ($request->masuk_status_hubungan_keluarga === 'kepala_keluarga') {
                    Penduduk::where('keluarga_id', $request->masuk_keluarga_id)
                        ->where('status_hubungan_keluarga', 'kepala_keluarga')
                        ->update(['status_hubungan_keluarga' => 'lainnya']);
                }

                $newPenduduk = Penduduk::create([
                    'keluarga_id' => $request->masuk_keluarga_id,
                    'nik' => $request->masuk_nik,
                    'nama_lengkap' => $request->masuk_nama_lengkap,
                    'tempat_lahir' => $request->masuk_tempat_lahir,
                    'tanggal_lahir' => $request->masuk_tanggal_lahir,
                    'jenis_kelamin' => $request->masuk_jenis_kelamin,
                    'agama' => $request->masuk_agama ?? 'islam',
                    'pendidikan_terakhir' => $request->masuk_pendidikan_terakhir ?? 'tidak_sekolah',
                    'pekerjaan' => $request->masuk_pekerjaan ?? 'Belum / Tidak Bekerja',
                    'status_perkawinan' => $request->masuk_status_perkawinan ?? 'belum_kawin',
                    'status_hubungan_keluarga' => $request->masuk_status_hubungan_keluarga ?? 'lainnya',
                    'kewarganegaraan' => $request->masuk_kewarganegaraan,
                    'golongan_darah' => $request->masuk_golongan_darah,
                    'no_paspor' => $request->masuk_no_paspor,
                    'no_kitas_kitap' => $request->masuk_no_kitas_kitap,
                    'nama_ayah' => $request->masuk_nama_ayah,
                    'nama_ibu' => $request->masuk_nama_ibu,
                    'no_telepon' => $request->masuk_no_telepon,
                    'is_asuransi_kesehatan' => $isAsuransi,
                    'is_disabilitas' => $isDisabilitas,
                    'jenis_disabilitas' => $request->masuk_jenis_disabilitas,
                    'status' => 'aktif',
                ]);

                $data['penduduk_id'] = $newPenduduk->id;
                MutasiPenduduk::create($data);
            });
        } else {
            DB::transaction(function () use ($request, $data, &$promotedMessage) {
                MutasiPenduduk::create($data);

                // Auto update Penduduk status
                $penduduk = Penduduk::find($request->penduduk_id);
                $oldStatusHubungan = $penduduk->status_hubungan_keluarga;
                $keluargaId = $penduduk->keluarga_id;

                if ($request->jenis_mutasi === 'mati') {
                    $penduduk->status = 'meninggal';
                } elseif ($request->jenis_mutasi === 'pindah_keluar') {
                    $penduduk->status = 'pindah';
                }

                // If they were the kepala_keluarga, change to lainnya and promote oldest active member
                if ($oldStatusHubungan === 'kepala_keluarga') {
                    $penduduk->status_hubungan_keluarga = 'lainnya';
                    $penduduk->save();

                    // Find oldest active remaining family member
                    $oldestActive = Penduduk::where('keluarga_id', $keluargaId)
                        ->where('status', 'aktif')
                        ->where('id', '!=', $penduduk->id)
                        ->orderBy('tanggal_lahir', 'asc')
                        ->first();

                    if ($oldestActive) {
                        $oldestActive->status_hubungan_keluarga = 'kepala_keluarga';
                        $oldestActive->save();
                        $promotedMessage = " Penduduk bernama <strong>" . $oldestActive->nama_lengkap . "</strong> otomatis dipromosikan menjadi Kepala Keluarga baru.";
                    } else {
                        $promotedMessage = " Anggota keluarga aktif lainnya tidak ditemukan. Kartu Keluarga tersebut sekarang tidak memiliki anggota aktif.";
                    }
                } else {
                    $penduduk->save();
                }
            });
        }

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil ditambahkan!' . $promotedMessage);
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
        $isMasuk = $request->input('jenis_mutasi') === 'pindah_masuk';

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
        } elseif ($isMasuk) {
            $rules['masuk_keluarga_id'] = 'required|exists:keluarga,id';
            $rules['masuk_nik'] = 'required|string|size:16|unique:penduduk,nik,' . $mutasiPenduduk->penduduk_id;
            $rules['masuk_nama_lengkap'] = 'required|string|max:255';
            $rules['masuk_tempat_lahir'] = 'nullable|string|max:100';
            $rules['masuk_tanggal_lahir'] = 'required|date';
            $rules['masuk_jenis_kelamin'] = 'required|in:laki-laki,perempuan';
            $rules['masuk_agama'] = 'nullable|in:islam,kristen,katolik,hindu,buddha,konghucu,lainnya';
            $rules['masuk_pendidikan_terakhir'] = 'nullable|in:tidak_sekolah,sd,smp,sma,d1,d2,d3,s1,s2,s3';
            $rules['masuk_pekerjaan'] = 'nullable|string|max:100';
            $rules['masuk_status_perkawinan'] = 'nullable|in:belum_kawin,kawin,cerai_hidup,cerai_mati';
            $rules['masuk_status_hubungan_keluarga'] = 'nullable|in:kepala_keluarga,istri,anak,menantu,cucu,orang_tua,mertua,famili_lain,lainnya';
            $rules['masuk_kewarganegaraan'] = 'required|in:WNI,WNA';
            $rules['masuk_golongan_darah'] = 'nullable|string|max:5';
            $rules['masuk_no_paspor'] = 'nullable|string|max:50';
            $rules['masuk_no_kitas_kitap'] = 'nullable|string|max:50';
            $rules['masuk_nama_ayah'] = 'nullable|string|max:255';
            $rules['masuk_nama_ibu'] = 'nullable|string|max:255';
            $rules['masuk_no_telepon'] = 'nullable|string|max:20';
            $rules['masuk_is_asuransi_kesehatan'] = 'nullable|boolean';
            $rules['masuk_is_disabilitas'] = 'nullable|boolean';
            $rules['masuk_jenis_disabilitas'] = 'nullable|string|max:255';
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

        $promotedMessage = '';

        DB::transaction(function () use ($request, $mutasiPenduduk, $data, $isLahir, $isMasuk, &$promotedMessage) {
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
            } elseif ($isMasuk) {
                // Update or create inbound resident
                $resident = $mutasiPenduduk->penduduk;
                $isAsuransi = $request->has('masuk_is_asuransi_kesehatan') ? (bool) $request->masuk_is_asuransi_kesehatan : false;
                $isDisabilitas = $request->has('masuk_is_disabilitas') ? (bool) $request->masuk_is_disabilitas : false;

                // If status_hubungan_keluarga is kepala_keluarga, demote the existing one in the KK
                if ($request->masuk_status_hubungan_keluarga === 'kepala_keluarga') {
                    Penduduk::where('keluarga_id', $request->masuk_keluarga_id)
                        ->where('status_hubungan_keluarga', 'kepala_keluarga')
                        ->where('id', '!=', $resident ? $resident->id : 0)
                        ->update(['status_hubungan_keluarga' => 'lainnya']);
                }

                $residentData = [
                    'keluarga_id' => $request->masuk_keluarga_id,
                    'nik' => $request->masuk_nik,
                    'nama_lengkap' => $request->masuk_nama_lengkap,
                    'tempat_lahir' => $request->masuk_tempat_lahir,
                    'tanggal_lahir' => $request->masuk_tanggal_lahir,
                    'jenis_kelamin' => $request->masuk_jenis_kelamin,
                    'agama' => $request->masuk_agama ?? 'islam',
                    'pendidikan_terakhir' => $request->masuk_pendidikan_terakhir ?? 'tidak_sekolah',
                    'pekerjaan' => $request->masuk_pekerjaan ?? 'Belum / Tidak Bekerja',
                    'status_perkawinan' => $request->masuk_status_perkawinan ?? 'belum_kawin',
                    'status_hubungan_keluarga' => $request->masuk_status_hubungan_keluarga ?? 'lainnya',
                    'kewarganegaraan' => $request->masuk_kewarganegaraan,
                    'golongan_darah' => $request->masuk_golongan_darah,
                    'no_paspor' => $request->masuk_no_paspor,
                    'no_kitas_kitap' => $request->masuk_no_kitas_kitap,
                    'nama_ayah' => $request->masuk_nama_ayah,
                    'nama_ibu' => $request->masuk_nama_ibu,
                    'no_telepon' => $request->masuk_no_telepon,
                    'is_asuransi_kesehatan' => $isAsuransi,
                    'is_disabilitas' => $isDisabilitas,
                    'jenis_disabilitas' => $request->masuk_jenis_disabilitas,
                    'status' => 'aktif',
                ];

                if ($resident) {
                    $resident->update($residentData);
                    $data['penduduk_id'] = $resident->id;
                } else {
                    $newResident = Penduduk::create($residentData);
                    $data['penduduk_id'] = $newResident->id;
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
                $oldStatusHubungan = $newPenduduk->status_hubungan_keluarga;
                $keluargaId = $newPenduduk->keluarga_id;

                if ($request->jenis_mutasi === 'mati') {
                    $newPenduduk->status = 'meninggal';
                } elseif ($request->jenis_mutasi === 'pindah_keluar') {
                    $newPenduduk->status = 'pindah';
                }

                // If they were the kepala_keluarga, change to lainnya and promote oldest active member
                if ($oldStatusHubungan === 'kepala_keluarga') {
                    $newPenduduk->status_hubungan_keluarga = 'lainnya';
                    $newPenduduk->save();

                    // Find oldest active remaining family member
                    $oldestActive = Penduduk::where('keluarga_id', $keluargaId)
                        ->where('status', 'aktif')
                        ->where('id', '!=', $newPenduduk->id)
                        ->orderBy('tanggal_lahir', 'asc')
                        ->first();

                    if ($oldestActive) {
                        $oldestActive->status_hubungan_keluarga = 'kepala_keluarga';
                        $oldestActive->save();
                        $promotedMessage = " Penduduk bernama <strong>" . $oldestActive->nama_lengkap . "</strong> otomatis dipromosikan menjadi Kepala Keluarga baru.";
                    } else {
                        $promotedMessage = " Anggota keluarga aktif lainnya tidak ditemukan. Kartu Keluarga tersebut sekarang tidak memiliki anggota aktif.";
                    }
                } else {
                    $newPenduduk->save();
                }
            }
        });

        return redirect()->route('admin.mutasi-penduduk.index')->with('success', 'Catatan mutasi penduduk berhasil diperbarui!' . $promotedMessage);
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

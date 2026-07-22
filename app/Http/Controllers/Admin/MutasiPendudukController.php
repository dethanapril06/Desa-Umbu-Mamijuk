<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MutasiPendudukExport;
use App\Http\Controllers\Controller;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\Dusun;
use App\Models\RtRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

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

        $mutasiList = $query->select('mutasi_penduduk.*')
            ->join('penduduk', 'mutasi_penduduk.penduduk_id', '=', 'penduduk.id')
            ->join('keluarga', 'penduduk.keluarga_id', '=', 'keluarga.id')
            ->orderBy('keluarga.no_kk', 'asc')
            ->paginate(15);
        $dusunList = Dusun::where('is_active', true)->orderBy('nama')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw')->orderBy('no_rt')->get();

        return view('admin.mutasi.index', compact('mutasiList', 'search', 'jenis', 'dusunList', 'rtRwList'));
    }

    public function report(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'jenis_mutasi' => 'nullable|in:mati,pindah_masuk,pindah_keluar',
            'dusun_id' => 'nullable|exists:dusun,id',
            'rt_rw_id' => 'nullable|exists:rt_rw,id',
            'tanggal_mutasi_mulai' => 'nullable|date',
            'tanggal_mutasi_selesai' => 'nullable|date|after_or_equal:tanggal_mutasi_mulai',
        ]);

        return Excel::download(new MutasiPendudukExport($filters), 'report-mutasi-penduduk-' . now()->format('Ymd') . '.xlsx');
    }

    public function create(): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('id', 'asc')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw', 'asc')->orderBy('no_rt', 'asc')->get();
        $pendudukList = Penduduk::with('keluarga.rtRw.dusun')->where('status', 'aktif')->orderBy('nama_lengkap', 'asc')->get();
        $keluargaList = Keluarga::with(['kepalaKeluarga', 'rtRw.dusun'])->orderBy('no_kk', 'asc')->get();
        $pekerjaanList = $this->getPekerjaanList();
        return view('admin.mutasi.create', compact('dusunList', 'rtRwList', 'pendudukList', 'keluargaList', 'pekerjaanList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $isMasuk = $request->input('jenis_mutasi') === 'pindah_masuk';

        $rules = [
            'filter_dusun_id' => 'required|exists:dusun,id',
            'filter_rt_rw_id' => 'required|exists:rt_rw,id',
            'jenis_mutasi' => 'required|in:mati,pindah_masuk,pindah_keluar',
            'tanggal_mutasi' => 'required|date',
            'no_surat' => 'required|string|max:100',
            'alamat_tujuan' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        if ($isMasuk) {
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

        $messages = [
            'filter_dusun_id.required' => 'Dusun wajib dipilih terlebih dahulu.',
            'filter_dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'filter_rt_rw_id.required' => 'RT / RW wajib dipilih terlebih dahulu.',
            'filter_rt_rw_id.exists' => 'RT / RW yang dipilih tidak valid.',
            'penduduk_id.required' => 'Penduduk wajib dipilih terlebih dahulu.',
            'penduduk_id.exists' => 'Penduduk yang dipilih tidak valid.',
            'masuk_keluarga_id.required' => 'Kartu Keluarga (KK) tujuan wajib dipilih.',
            'masuk_keluarga_id.exists' => 'Kartu Keluarga (KK) yang dipilih tidak valid.',
            'jenis_mutasi.required' => 'Jenis mutasi wajib dipilih.',
            'tanggal_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'no_surat.required' => 'Nomor surat keterangan / pengantar wajib diisi.',
            'masuk_nik.required' => 'NIK wajib diisi.',
            'masuk_nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'masuk_tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'masuk_jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'masuk_kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['lampiran', 'filter_dusun_id', 'filter_rt_rw_id']);

        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        $promotedMessage = '';

        if ($isMasuk) {
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
        $dusunList = Dusun::where('is_active', true)->orderBy('id', 'asc')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw', 'asc')->orderBy('no_rt', 'asc')->get();
        $pendudukList = Penduduk::with('keluarga.rtRw.dusun')->orderBy('nama_lengkap', 'asc')->get();
        $keluargaList = Keluarga::with(['kepalaKeluarga', 'rtRw.dusun'])->orderBy('no_kk', 'asc')->get();
        $pekerjaanList = $this->getPekerjaanList();
        return view('admin.mutasi.edit', compact('mutasiPenduduk', 'dusunList', 'rtRwList', 'pendudukList', 'keluargaList', 'pekerjaanList'));
    }

    public function update(Request $request, MutasiPenduduk $mutasiPenduduk): RedirectResponse
    {
        $this->normalizeInput($request);

        $isMasuk = $request->input('jenis_mutasi') === 'pindah_masuk';

        $rules = [
            'filter_dusun_id' => 'required|exists:dusun,id',
            'filter_rt_rw_id' => 'required|exists:rt_rw,id',
            'jenis_mutasi' => 'required|in:mati,pindah_masuk,pindah_keluar',
            'tanggal_mutasi' => 'required|date',
            'no_surat' => 'required|string|max:100',
            'alamat_tujuan' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        if ($isMasuk) {
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

        $messages = [
            'filter_dusun_id.required' => 'Dusun wajib dipilih terlebih dahulu.',
            'filter_dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'filter_rt_rw_id.required' => 'RT / RW wajib dipilih terlebih dahulu.',
            'filter_rt_rw_id.exists' => 'RT / RW yang dipilih tidak valid.',
            'penduduk_id.required' => 'Penduduk wajib dipilih terlebih dahulu.',
            'penduduk_id.exists' => 'Penduduk yang dipilih tidak valid.',
            'masuk_keluarga_id.required' => 'Kartu Keluarga (KK) tujuan wajib dipilih.',
            'masuk_keluarga_id.exists' => 'Kartu Keluarga (KK) yang dipilih tidak valid.',
            'jenis_mutasi.required' => 'Jenis mutasi wajib dipilih.',
            'tanggal_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'no_surat.required' => 'Nomor surat keterangan / pengantar wajib diisi.',
            'masuk_nik.required' => 'NIK wajib diisi.',
            'masuk_nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'masuk_tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'masuk_jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'masuk_kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['lampiran', 'filter_dusun_id', 'filter_rt_rw_id']);

        if ($request->hasFile('lampiran')) {
            if ($mutasiPenduduk->lampiran && Storage::disk('public')->exists($mutasiPenduduk->lampiran)) {
                Storage::disk('public')->delete($mutasiPenduduk->lampiran);
            }
            $path = $request->file('lampiran')->store('lampiran/mutasi', 'public');
            $data['lampiran'] = $path;
        }

        $promotedMessage = '';

        DB::transaction(function () use ($request, $mutasiPenduduk, $data, $isMasuk, &$promotedMessage) {
            if ($isMasuk) {
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

    private function getPekerjaanList(): array
    {
        $list = [
            'Belum / Tidak Bekerja',
            'Pelajar / Mahasiswa',
            'PNS / ASN',
            'PPPK',
            'TNI / POLRI',
            'Karyawan Swasta / BUMN / BUMD',
            'Petani / Pekebun / Peternak',
            'Nelayan',
            'Wiraswasta / Pedagang',
            'Buruh Harian Lepas',
            'Sopir / Pengemudi',
            'Pensiunan'
        ];
        sort($list);
        return $list;
    }

    private function normalizeInput(Request $request): void
    {
        $noSpaceFields = ['masuk_nik', 'masuk_no_telepon', 'masuk_no_paspor', 'masuk_no_kitas_kitap'];
        foreach ($noSpaceFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', '', $request->input($field));
                $request->merge([$field => $cleaned]);
            }
        }

        $titleFields = ['masuk_nama_lengkap', 'masuk_tempat_lahir', 'masuk_nama_ayah', 'masuk_nama_ibu', 'masuk_jenis_disabilitas'];
        foreach ($titleFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $request->merge([$field => $cleaned]);
            }
        }

        if ($request->has('no_surat') && is_string($request->input('no_surat')) && !empty($request->input('no_surat'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('no_surat')));
            $cleaned = mb_strtoupper($cleaned, 'UTF-8');
            $request->merge(['no_surat' => $cleaned]);
        }

        $textFields = ['keterangan', 'alamat_tujuan', 'alamat_asal'];
        foreach ($textFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $lines = preg_split('/\r\n|\r|\n/', $request->input($field));
                $cleanedLines = array_map(function ($line) {
                    return preg_replace('/[^\S\r\n]+/', ' ', trim($line));
                }, $lines);
                $cleaned = trim(implode("\n", $cleanedLines));
                $request->merge([$field => $cleaned]);
            }
        }
    }
}

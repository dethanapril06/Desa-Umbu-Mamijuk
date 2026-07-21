<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\RtRw;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PendudukController extends Controller
{
    public function index(Request $request): View
    {
        $searchField = $request->input('search_field', 'nama_lengkap');
        $search      = trim((string) $request->input('search', ''));

        $query = Penduduk::with(['keluarga.rtRw.dusun']);

        $fulltextFields = ['nama_lengkap', 'tempat_lahir', 'pekerjaan', 'nama_ayah', 'nama_ibu'];
        $likeFields     = ['nik', 'no_telepon', 'agama', 'status_hubungan_keluarga', 'no_paspor', 'no_kitas_kitap'];
        $exactFields    = ['jenis_kelamin', 'status', 'pendidikan_terakhir', 'status_perkawinan', 'golongan_darah', 'kewarganegaraan', 'tanggal_lahir'];

        if ($search !== '') {
            if (in_array($searchField, $fulltextFields, true)) {
                if (mb_strlen($search) >= 3) {
                    $query->where(function ($q) use ($searchField, $search) {
                        $q->whereFullText($searchField, $search)
                          ->orWhere($searchField, 'like', "%{$search}%");
                    });
                } else {
                    $query->where($searchField, 'like', "%{$search}%");
                }
            } elseif (in_array($searchField, $likeFields, true)) {
                $query->where($searchField, 'like', "%{$search}%");
            } elseif (in_array($searchField, $exactFields, true)) {
                $query->where($searchField, $search);
            }
        }

        $pendudukList = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        return view('admin.penduduk.index', compact('pendudukList', 'search', 'searchField'));
    }

    public function create(Request $request): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('id', 'asc')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw', 'asc')->orderBy('no_rt', 'asc')->get();
        $keluargaList = Keluarga::with(['rtRw.dusun', 'penduduk' => function ($query) {
            $query->where('status_hubungan_keluarga', 'kepala_keluarga');
        }])->orderBy('no_kk', 'asc')->get();
        $selectedKeluargaId = $request->input('keluarga_id');
        $pekerjaanList = $this->getPekerjaanList();

        return view('admin.penduduk.create', compact('dusunList', 'rtRwList', 'keluargaList', 'selectedKeluargaId', 'pekerjaanList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'dusun_id' => 'required|exists:dusun,id',
            'rt_rw_id' => 'required|exists:rt_rw,id',
            'keluarga_id' => 'required|exists:keluarga,id',
            'nik' => 'required|string|size:16|unique:penduduk,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'agama' => 'nullable|in:islam,kristen,katolik,hindu,buddha,konghucu,lainnya',
            'pendidikan_terakhir' => 'nullable|in:tidak_sekolah,sd,smp,sma,d1,d2,d3,s1,s2,s3',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'status_hubungan_keluarga' => 'nullable|in:kepala_keluarga,istri,anak,menantu,cucu,orang_tua,mertua,famili_lain,lainnya',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'golongan_darah' => 'nullable|string|max:5',
            'no_paspor' => 'nullable|string|max:50',
            'no_kitas_kitap' => 'nullable|string|max:50',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'is_asuransi_kesehatan' => 'nullable|boolean',
            'is_disabilitas' => 'nullable|boolean',
            'jenis_disabilitas' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,pindah,meninggal',
            'keterangan' => 'nullable|string',
        ];

        $messages = [
            'dusun_id.required' => 'Dusun wajib dipilih terlebih dahulu.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'rt_rw_id.required' => 'RT / RW wajib dipilih terlebih dahulu.',
            'rt_rw_id.exists' => 'RT / RW yang dipilih tidak valid.',
            'keluarga_id.required' => 'Kartu Keluarga (KK) wajib dipilih terlebih dahulu.',
            'keluarga_id.exists' => 'Kartu Keluarga (KK) yang dipilih tidak valid.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus berjumlah 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status.required' => 'Status penduduk wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['dusun_id', 'rt_rw_id']);
        $data['is_asuransi_kesehatan'] = $request->has('is_asuransi_kesehatan') ? (bool) $request->is_asuransi_kesehatan : false;
        $data['is_disabilitas'] = $request->has('is_disabilitas') ? (bool) $request->is_disabilitas : false;

        // If status_hubungan_keluarga is kepala_keluarga, demote the existing one in the KK
        if ($data['status_hubungan_keluarga'] === 'kepala_keluarga') {
            Penduduk::where('keluarga_id', $request->keluarga_id)
                ->where('status_hubungan_keluarga', 'kepala_keluarga')
                ->update(['status_hubungan_keluarga' => 'lainnya']);
        }

        Penduduk::create($data);

        return redirect()->route('admin.keluarga.show', $request->keluarga_id)->with('success', 'Penduduk berhasil ditambahkan ke keluarga!');
    }

    public function show(Penduduk $penduduk): View
    {
        $penduduk->load('keluarga.rtRw.dusun');
        return view('admin.penduduk.show', compact('penduduk'));
    }

    public function edit(Penduduk $penduduk): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('id', 'asc')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw', 'asc')->orderBy('no_rt', 'asc')->get();
        $keluargaList = Keluarga::with(['rtRw.dusun', 'penduduk' => function ($query) {
            $query->where('status_hubungan_keluarga', 'kepala_keluarga');
        }])->orderBy('no_kk', 'asc')->get();
        $pekerjaanList = $this->getPekerjaanList();
        return view('admin.penduduk.edit', compact('penduduk', 'dusunList', 'rtRwList', 'keluargaList', 'pekerjaanList'));
    }

    public function update(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'dusun_id' => 'required|exists:dusun,id',
            'rt_rw_id' => 'required|exists:rt_rw,id',
            'keluarga_id' => 'required|exists:keluarga,id',
            'nik' => 'required|string|size:16|unique:penduduk,nik,' . $penduduk->id,
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'agama' => 'nullable|in:islam,kristen,katolik,hindu,buddha,konghucu,lainnya',
            'pendidikan_terakhir' => 'nullable|in:tidak_sekolah,sd,smp,sma,d1,d2,d3,s1,s2,s3',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'status_hubungan_keluarga' => 'nullable|in:kepala_keluarga,istri,anak,menantu,cucu,orang_tua,mertua,famili_lain,lainnya',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'golongan_darah' => 'nullable|string|max:5',
            'no_paspor' => 'nullable|string|max:50',
            'no_kitas_kitap' => 'nullable|string|max:50',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'is_asuransi_kesehatan' => 'nullable|boolean',
            'is_disabilitas' => 'nullable|boolean',
            'jenis_disabilitas' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,pindah,meninggal',
            'keterangan' => 'nullable|string',
        ];

        $messages = [
            'dusun_id.required' => 'Dusun wajib dipilih terlebih dahulu.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'rt_rw_id.required' => 'RT / RW wajib dipilih terlebih dahulu.',
            'rt_rw_id.exists' => 'RT / RW yang dipilih tidak valid.',
            'keluarga_id.required' => 'Kartu Keluarga (KK) wajib dipilih terlebih dahulu.',
            'keluarga_id.exists' => 'Kartu Keluarga (KK) yang dipilih tidak valid.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus berjumlah 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status.required' => 'Status penduduk wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['dusun_id', 'rt_rw_id']);
        $data['is_asuransi_kesehatan'] = $request->has('is_asuransi_kesehatan') ? (bool) $request->is_asuransi_kesehatan : false;
        $data['is_disabilitas'] = $request->has('is_disabilitas') ? (bool) $request->is_disabilitas : false;

        // If status_hubungan_keluarga is kepala_keluarga, demote the existing one in the KK
        if ($data['status_hubungan_keluarga'] === 'kepala_keluarga') {
            Penduduk::where('keluarga_id', $request->keluarga_id)
                ->where('status_hubungan_keluarga', 'kepala_keluarga')
                ->where('id', '!=', $penduduk->id)
                ->update(['status_hubungan_keluarga' => 'lainnya']);
        }

        $penduduk->update($data);

        return redirect()->route('admin.keluarga.show', $request->keluarga_id)->with('success', 'Data penduduk berhasil diperbarui!');
    }

    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        $keluargaId = $penduduk->keluarga_id;
        $penduduk->delete();

        return redirect()->route('admin.keluarga.show', $keluargaId)->with('success', 'Data penduduk berhasil dihapus!');
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
        $noSpaceFields = ['nik', 'no_telepon', 'no_paspor', 'no_kitas_kitap'];
        foreach ($noSpaceFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', '', $request->input($field));
                $request->merge([$field => $cleaned]);
            }
        }

        $titleFields = ['nama_lengkap', 'tempat_lahir', 'nama_ayah', 'nama_ibu', 'jenis_disabilitas'];
        foreach ($titleFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $request->merge([$field => $cleaned]);
            }
        }

        $textFields = ['keterangan'];
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

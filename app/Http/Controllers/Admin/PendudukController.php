<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PendudukController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $jk     = $request->input('jenis_kelamin');

        $query = Penduduk::with(['keluarga.rtRw.dusun']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($jk) {
            $query->where('jenis_kelamin', $jk);
        }

        $pendudukList = $query->orderBy('id', 'desc')->paginate(15);
        return view('admin.penduduk.index', compact('pendudukList', 'search', 'status', 'jk'));
    }

    public function create(Request $request): View
    {
        $keluargaList = Keluarga::orderBy('no_kk', 'asc')->get();
        
        // Option to pre-select keluarga_id from the Keluarga details page
        $selectedKeluargaId = $request->input('keluarga_id');

        return view('admin.penduduk.create', compact('keluargaList', 'selectedKeluargaId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
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
        ]);

        $data = $request->all();
        $data['is_asuransi_kesehatan'] = $request->has('is_asuransi_kesehatan') ? (bool) $request->is_asuransi_kesehatan : false;
        $data['is_disabilitas'] = $request->has('is_disabilitas') ? (bool) $request->is_disabilitas : false;

        // If status_hubungan_keluarga is kepala_keluarga, check if there's already one in the KK
        if ($data['status_hubungan_keluarga'] === 'kepala_keluarga') {
            $existingKepala = Penduduk::where('keluarga_id', $request->keluarga_id)
                ->where('status_hubungan_keluarga', 'kepala_keluarga')
                ->exists();
            if ($existingKepala) {
                return redirect()->back()->withInput()->withErrors(['status_hubungan_keluarga' => 'Kartu Keluarga ini sudah memiliki Kepala Keluarga! Silakan sesuaikan hubungan keluarga penduduk.']);
            }
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
        $keluargaList = Keluarga::orderBy('no_kk', 'asc')->get();
        return view('admin.penduduk.edit', compact('penduduk', 'keluargaList'));
    }

    public function update(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $request->validate([
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
        ]);

        $data = $request->all();
        $data['is_asuransi_kesehatan'] = $request->has('is_asuransi_kesehatan') ? (bool) $request->is_asuransi_kesehatan : false;
        $data['is_disabilitas'] = $request->has('is_disabilitas') ? (bool) $request->is_disabilitas : false;

        // If status_hubungan_keluarga is changed to kepala_keluarga, check if there's already one in the KK
        if ($data['status_hubungan_keluarga'] === 'kepala_keluarga' && $penduduk->status_hubungan_keluarga !== 'kepala_keluarga') {
            $existingKepala = Penduduk::where('keluarga_id', $request->keluarga_id)
                ->where('status_hubungan_keluarga', 'kepala_keluarga')
                ->where('id', '!=', $penduduk->id)
                ->exists();
            if ($existingKepala) {
                return redirect()->back()->withInput()->withErrors(['status_hubungan_keluarga' => 'Kartu Keluarga ini sudah memiliki Kepala Keluarga!']);
            }
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
}

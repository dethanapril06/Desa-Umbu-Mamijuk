<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KeluargaExport;
use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\RtRw;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

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

        $keluargaList = $query->orderBy('no_kk', 'asc')->paginate(15);
        $dusunList = Dusun::where('is_active', true)->orderBy('nama')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw')->orderBy('no_rt')->get();

        return view('admin.keluarga.index', compact('keluargaList', 'search', 'dusunList', 'rtRwList'));
    }

    public function report(Request $request)
    {
        $filters = $request->validate([
            'dusun_id' => 'nullable|exists:dusun,id',
            'rt_rw_id' => 'nullable|exists:rt_rw,id',
            'tanggal_terdaftar_mulai' => 'nullable|date',
            'tanggal_terdaftar_selesai' => 'nullable|date|after_or_equal:tanggal_terdaftar_mulai',
            'status_kepala_keluarga' => 'nullable|in:ada,belum_ada',
        ]);

        return Excel::download(new KeluargaExport($filters), 'report-keluarga-' . now()->format('Ymd') . '.xlsx');
    }

    public function create(): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('id', 'asc')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw', 'asc')->orderBy('no_rt', 'asc')->get();
        return view('admin.keluarga.create', compact('dusunList', 'rtRwList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'dusun_id' => 'required|exists:dusun,id',
            'no_kk' => 'required|string|size:16|unique:keluarga,no_kk',
            'rt_rw_id' => 'required|exists:rt_rw,id',
            'alamat' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_terdaftar' => 'nullable|date',
        ];

        $messages = [
            'dusun_id.required' => 'Dusun wajib dipilih terlebih dahulu.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'rt_rw_id.required' => 'RT / RW wajib dipilih terlebih dahulu.',
            'rt_rw_id.exists' => 'RT / RW yang dipilih tidak valid.',
            'no_kk.required' => 'Nomor Kartu Keluarga (KK) wajib diisi.',
            'no_kk.size' => 'Nomor KK harus berjumlah 16 digit.',
            'no_kk.unique' => 'Nomor KK sudah terdaftar di sistem.',
            'alamat.required' => 'Alamat wajib diisi.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['dusun_id']);
        $keluarga = Keluarga::create($data);

        return redirect()->route('admin.keluarga.show', $keluarga->id)
                         ->with('success', 'Kartu Keluarga baru berhasil disimpan! Silakan klik tombol "Tambah Anggota" untuk menambahkan Kepala Keluarga atau anggota keluarga.');
    }

    public function show(Keluarga $keluarga): View
    {
        $keluarga->load(['rtRw.dusun', 'penduduk' => function ($query) {
            $query->where('status', 'aktif')
                  ->orderByRaw("FIELD(status_hubungan_keluarga, 'kepala_keluarga', 'istri', 'anak', 'menantu', 'cucu', 'orang_tua', 'mertua', 'famili_lain', 'lainnya')");
        }]);

        $kepalaKeluarga = $keluarga->penduduk->where('status_hubungan_keluarga', 'kepala_keluarga')->first();

        return view('admin.keluarga.show', compact('keluarga', 'kepalaKeluarga'));
    }

    public function edit(Keluarga $keluarga): View
    {
        $dusunList = Dusun::where('is_active', true)->orderBy('id', 'asc')->get();
        $rtRwList = RtRw::with('dusun')->orderBy('no_rw', 'asc')->orderBy('no_rt', 'asc')->get();
        return view('admin.keluarga.edit', compact('keluarga', 'dusunList', 'rtRwList'));
    }

    public function update(Request $request, Keluarga $keluarga): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'dusun_id' => 'required|exists:dusun,id',
            'no_kk' => 'required|string|size:16|unique:keluarga,no_kk,' . $keluarga->id,
            'rt_rw_id' => 'required|exists:rt_rw,id',
            'alamat' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_terdaftar' => 'nullable|date',
        ];

        $messages = [
            'dusun_id.required' => 'Dusun wajib dipilih terlebih dahulu.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'rt_rw_id.required' => 'RT / RW wajib dipilih terlebih dahulu.',
            'rt_rw_id.exists' => 'RT / RW yang dipilih tidak valid.',
            'no_kk.required' => 'Nomor Kartu Keluarga (KK) wajib diisi.',
            'no_kk.size' => 'Nomor KK harus berjumlah 16 digit.',
            'no_kk.unique' => 'Nomor KK sudah terdaftar di sistem.',
            'alamat.required' => 'Alamat wajib diisi.',
        ];

        $request->validate($rules, $messages);

        $data = $request->except(['dusun_id']);
        $keluarga->update($data);

        return redirect()->route('admin.keluarga.index')->with('success', 'Kartu Keluarga berhasil diperbarui!');
    }

    public function destroy(Keluarga $keluarga): RedirectResponse
    {
        // Cek penduduk aktif (non-soft-deleted) yang masih terdaftar
        if ($keluarga->penduduk()->count() > 0) {
            return redirect()->route('admin.keluarga.index')
                ->with('error', 'Tidak dapat menghapus KK ini karena memiliki anggota keluarga terdaftar! Silakan hapus atau pindahkan anggota keluarga terlebih dahulu.');
        }

        // Force-delete penduduk yang sudah soft-deleted agar FK constraint tidak gagal
        // (MySQL melihat semua baris fisik, termasuk soft-deleted)
        Penduduk::withTrashed()
            ->where('keluarga_id', $keluarga->id)
            ->forceDelete();

        $keluarga->delete();
        return redirect()->route('admin.keluarga.index')->with('success', 'Kartu Keluarga berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        // 1. Hapus seluruh spasi pada no_kk dan kode_pos
        foreach (['no_kk', 'kode_pos'] as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', '', $request->input($field));
                $request->merge([$field => $cleaned]);
            }
        }

        // 2. Bersihkan spasi berlebih & Capital Each Word pada alamat (multiline safe)
        if ($request->has('alamat') && is_string($request->input('alamat')) && !empty($request->input('alamat'))) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('alamat'));
            $cleanedLines = [];
            foreach ($lines as $line) {
                $cleanedLines[] = preg_replace('/[^\S\r\n]+/', ' ', trim($line));
            }
            $text = implode("\n", $cleanedLines);
            $text = preg_replace('/\n{3,}/', "\n\n", trim($text));

            $text = mb_convert_case(mb_strtolower($text, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['alamat' => $text]);
        }
    }
}

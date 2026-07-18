<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TanggapanPengaduan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class TanggapanPengaduanController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'pengaduan_id'  => 'required|exists:pengaduan,id',
            'isi_tanggapan' => 'required|string',
            'lampiran'      => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpeg,png,jpg,webp|max:2048',
        ], [
            'isi_tanggapan.required' => 'Isi tanggapan wajib diisi.',
            'lampiran.mimes'         => 'Format lampiran harus pdf, doc, docx, xls, xlsx, jpeg, png, jpg, atau webp.',
            'lampiran.max'           => 'Ukuran lampiran maksimal adalah 2MB.',
        ]);

        $data = [
            'pengaduan_id'  => $request->pengaduan_id,
            'user_id'       => auth()->id(),
            'isi_tanggapan' => $request->isi_tanggapan,
        ];

        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('tanggapan', 'public');
            $data['lampiran'] = $path;
        }

        TanggapanPengaduan::create($data);

        // Update status pengaduan ke 'diproses' secara otomatis jika statusnya saat ini masih 'masuk'
        $pengaduan = Pengaduan::findOrFail($request->pengaduan_id);
        if ($pengaduan->status === 'masuk') {
            $pengaduan->update(['status' => 'diproses']);
        }

        return redirect()->route('admin.pengaduan.show', $request->pengaduan_id)
            ->with('success', 'Tanggapan berhasil dikirim dan disimpan!');
    }

    public function destroy(TanggapanPengaduan $tanggapan): RedirectResponse
    {
        $pengaduanId = $tanggapan->pengaduan_id;

        if ($tanggapan->lampiran && Storage::disk('public')->exists($tanggapan->lampiran)) {
            Storage::disk('public')->delete($tanggapan->lampiran);
        }

        $tanggapan->delete();

        return redirect()->route('admin.pengaduan.show', $pengaduanId)
            ->with('success', 'Tanggapan berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('isi_tanggapan') && is_string($request->input('isi_tanggapan')) && !empty($request->input('isi_tanggapan'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('isi_tanggapan')));
            $request->merge(['isi_tanggapan' => $cleaned]);
        }
    }
}

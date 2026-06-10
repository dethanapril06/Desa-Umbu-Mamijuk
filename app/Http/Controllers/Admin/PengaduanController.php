<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\KategoriPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PengaduanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $kategoriId = $request->input('kategori_pengaduan_id');

        $query = Pengaduan::with('kategoriPengaduan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_tiket', 'like', "%{$search}%")
                  ->orWhere('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($kategoriId) {
            $query->where('kategori_pengaduan_id', $kategoriId);
        }

        $pengaduanList = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoriList = KategoriPengaduan::all();
        $statusOptions = ['masuk', 'diproses', 'selesai', 'ditolak'];

        return view('admin.pengaduan.index', compact('pengaduanList', 'kategoriList', 'statusOptions', 'search', 'status', 'kategoriId'));
    }

    public function show(Pengaduan $pengaduan): View
    {
        $pengaduan->load(['kategoriPengaduan', 'tanggapanPengaduan.user']);
        $statusOptions = ['masuk', 'diproses', 'selesai', 'ditolak'];

        return view('admin.pengaduan.show', compact('pengaduan', 'statusOptions'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:masuk,diproses,selesai,ditolak',
        ]);

        $pengaduan->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pengaduan.show', $pengaduan->id)
            ->with('success', 'Status pengaduan berhasil diubah menjadi: ' . ucfirst($request->status));
    }

    public function destroy(Pengaduan $pengaduan): RedirectResponse
    {
        // Hapus file lampiran dari semua tanggapan pengaduan terkait
        foreach ($pengaduan->tanggapanPengaduan as $tanggapan) {
            if ($tanggapan->lampiran && Storage::disk('public')->exists($tanggapan->lampiran)) {
                Storage::disk('public')->delete($tanggapan->lampiran);
            }
        }

        // Hapus file lampiran pengaduan
        if ($pengaduan->lampiran && Storage::disk('public')->exists($pengaduan->lampiran)) {
            Storage::disk('public')->delete($pengaduan->lampiran);
        }

        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Data pengaduan dan semua tanggapannya berhasil dihapus!');
    }

    public function togglePublik(Pengaduan $pengaduan): RedirectResponse
    {
        $pengaduan->update([
            'is_publik' => !$pengaduan->is_publik,
        ]);

        $statusText = $pengaduan->is_publik ? 'dipublikasikan ke publik' : 'diubah menjadi privat';

        return redirect()->route('admin.pengaduan.show', $pengaduan->id)
            ->with('success', 'Status publikasi pengaduan berhasil ' . $statusText . '!');
    }
}

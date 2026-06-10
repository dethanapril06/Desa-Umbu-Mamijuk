<?php

namespace App\Http\Controllers\Admin;

// Note: KomentarBerita model is mapped to App\Models\KomentarBerita
use App\Http\Controllers\Controller;
use App\Models\KomentarBerita;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KomentarBeritaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = KomentarBerita::with('berita');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('komentar', 'like', "%{$search}%")
                  ->orWhereHas('berita', function ($q) use ($search) {
                      $q->where('judul', 'like', "%{$search}%");
                  });
        }

        $komentarList = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.komentar.index', compact('komentarList', 'search'));
    }

    public function toggleApprove(KomentarBerita $komentar): RedirectResponse
    {
        $komentar->is_approved = !$komentar->is_approved;
        $komentar->save();

        $statusText = $komentar->is_approved ? 'disetujui' : 'ditolak/disembunyikan';
        return redirect()->route('admin.komentar-berita.index')->with('success', "Komentar dari {$komentar->nama} berhasil {$statusText}!");
    }

    public function destroy(KomentarBerita $komentar): RedirectResponse
    {
        $name = $komentar->nama;
        $komentar->delete();

        return redirect()->route('admin.komentar-berita.index')->with('success', "Komentar dari {$name} berhasil dihapus!");
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\KomentarBerita;
use App\Models\ProfilDesa;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BeritaController extends Controller
{
    /**
     * Halaman daftar semua berita dengan filter kategori, search, dan pagination.
     */
    public function index(Request $request): View
    {
        $profilDesa = ProfilDesa::first();

        $kategori = KategoriBerita::withCount(['berita' => function ($q) {
            $q->where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])->orderBy('nama')->get();

        $query = Berita::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['kategoriBerita']);

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->whereHas('kategoriBerita', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $berita = $query->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.berita.index', compact('profilDesa', 'kategori', 'berita'));
    }

    /**
     * Halaman detail berita.
     */
    public function show(string $slug): View
    {
        $profilDesa = ProfilDesa::first();

        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with([
                'user',
                'kategoriBerita',
                'tags',
                'komentarBerita' => fn ($q) => $q->where('is_approved', true)->latest(),
            ])
            ->firstOrFail();

        $berita->increment('views');
        $berita->refresh();
        $berita->load([
            'user',
            'kategoriBerita',
            'tags',
            'komentarBerita' => fn ($q) => $q->where('is_approved', true)->latest(),
        ]);

        $beritaTerbaru = Berita::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $berita->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        $kategori = KategoriBerita::withCount(['berita' => function ($q) {
            $q->where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])->orderBy('nama')->get();

        $beritaTerkait = Berita::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $berita->id)
            ->where('kategori_berita_id', $berita->kategori_berita_id)
            ->with('kategoriBerita')
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($beritaTerkait->count() < 3) {
            $tambahan = Berita::where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where('id', '!=', $berita->id)
                ->whereNotIn('id', $beritaTerkait->pluck('id'))
                ->with('kategoriBerita')
                ->latest('published_at')
                ->take(3 - $beritaTerkait->count())
                ->get();

            $beritaTerkait = $beritaTerkait->concat($tambahan);
        }

        $wisataUnggulan = Wisata::where('is_published', true)
            ->where('is_unggulan', true)
            ->first()
            ?? Wisata::where('is_published', true)->latest()->first();

        $approvedComments = $berita->komentarBerita;
        $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags($berita->konten)) / 200));

        return view('frontend.berita.show', compact(
            'profilDesa',
            'berita',
            'beritaTerbaru',
            'kategori',
            'beritaTerkait',
            'wisataUnggulan',
            'approvedComments',
            'readingMinutes',
        ));
    }

    /**
     * Menyimpan komentar berita sebagai pending approval.
     */
    public function storeKomentar(Request $request, string $slug): RedirectResponse
    {
        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'komentar' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'komentar.required' => 'Komentar wajib diisi.',
            'komentar.min' => 'Komentar minimal 10 karakter.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        KomentarBerita::create([
            'berita_id' => $berita->id,
            'nama' => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'komentar' => $validated['komentar'],
            'is_approved' => false,
        ]);

        return back()
            ->with('komentar_success', 'Terima kasih! Komentar Anda berhasil dikirim dan akan tampil setelah disetujui admin.')
            ->withFragment('komentar');
    }
}

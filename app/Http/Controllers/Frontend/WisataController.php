<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KategoriWisata;
use App\Models\ProfilDesa;
use App\Models\UlasanWisata;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WisataController extends Controller
{
    /**
     * Halaman daftar semua wisata dengan filter kategori, search, dan pagination.
     */
    public function index(Request $request): View
    {
        $profilDesa = ProfilDesa::first();

        $kategori = KategoriWisata::withCount(['wisata' => function ($q) {
            $q->where('is_published', true);
        }])->get();

        $query = Wisata::where('is_published', true)
            ->with(['kategoriWisata', 'ulasanWisata']);

        // Filter by kategori
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->whereHas('kategoriWisata', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi_singkat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Sort: unggulan first, then latest
        $query->orderByDesc('is_unggulan')->latest();

        $wisata = $query->paginate(9)->withQueryString();

        return view('frontend.wisata.index', compact('profilDesa', 'wisata', 'kategori'));
    }

    /**
     * Halaman detail wisata.
     */
    public function show(string $slug): View
    {
        $wisata = Wisata::where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'kategoriWisata',
                'galeriWisata' => fn ($q) => $q->orderBy('urutan'),
                'fasilitasWisata',
                'tipsWisata' => fn ($q) => $q->orderBy('urutan'),
                'ruteWisata' => fn ($q) => $q->orderBy('urutan'),
                'ulasanWisata' => fn ($q) => $q->where('is_approved', true)->latest(),
            ])
            ->firstOrFail();

        // Rating statistics
        $ulasanApproved = $wisata->ulasanWisata;
        $totalUlasan = $ulasanApproved->count();
        $avgRating = $totalUlasan > 0 ? round($ulasanApproved->avg('rating'), 1) : 0;

        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $ulasanApproved->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'persen' => $totalUlasan > 0 ? round(($count / $totalUlasan) * 100) : 0,
            ];
        }

        // Wisata lainnya (max 3, exclude current)
        $wisataLainnya = Wisata::where('is_published', true)
            ->where('id', '!=', $wisata->id)
            ->with(['kategoriWisata', 'ulasanWisata' => fn ($q) => $q->where('is_approved', true)])
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('frontend.wisata.show', compact(
            'wisata',
            'totalUlasan',
            'avgRating',
            'ratingDistribution',
            'wisataLainnya',
        ));
    }

    /**
     * Menyimpan ulasan pengunjung sebagai pending approval.
     */
    public function storeUlasan(Request $request, string $slug): RedirectResponse
    {
        $wisata = Wisata::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'rating.required' => 'Rating wajib dipilih.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'ulasan.required' => 'Ulasan wajib diisi.',
            'ulasan.min' => 'Ulasan minimal 10 karakter.',
            'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        UlasanWisata::create([
            'wisata_id' => $wisata->id,
            'nama' => $validated['nama'],
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
            'is_approved' => false,
        ]);

        return back()
            ->with('ulasan_success', 'Terima kasih! Ulasan Anda berhasil dikirim dan akan ditampilkan setelah disetujui admin.')
            ->withFragment('ulasan');
    }
}

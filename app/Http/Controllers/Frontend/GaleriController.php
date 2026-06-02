<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AlbumGaleri;
use App\Models\Galeri;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GaleriController extends Controller
{
    /**
     * Halaman daftar semua galeri dengan filter album, search, dan pagination.
     */
    public function index(Request $request): View
    {
        $profilDesa = ProfilDesa::first();

        $album = AlbumGaleri::withCount('galeri')
            ->orderBy('nama')
            ->get();

        $query = Galeri::with('albumGaleri')
            ->when($request->filled('album') && $request->album !== 'semua', function ($q) use ($request) {
                $q->whereHas('albumGaleri', fn ($albumQuery) => $albumQuery->where('slug', $request->album));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $keyword = $request->q;

                $q->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('caption', 'like', "%{$keyword}%")
                        ->orWhereHas('albumGaleri', function ($albumQuery) use ($keyword) {
                            $albumQuery->where('nama', 'like', "%{$keyword}%")
                                ->orWhere('deskripsi', 'like', "%{$keyword}%");
                        });
                });
            })
            ->orderBy('urutan')
            ->latest();

        $galeri = $query->paginate(12)->withQueryString();

        return view('frontend.galeri.index', compact('profilDesa', 'album', 'galeri'));
    }
}

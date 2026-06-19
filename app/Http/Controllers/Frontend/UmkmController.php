<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UmkmController extends Controller
{
    /**
     * Display a listing of active UMKM with search and category filters.
     */
    public function index(Request $request): View
    {
        $profilDesa = ProfilDesa::first();
        
        $categories = [
            'Kuliner',
            'Fashion',
            'Kerajinan',
            'Pertanian',
            'Jasa',
            'Lainnya'
        ];

        $query = Umkm::active();

        // Filter by category
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        // Search by keyword
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $umkmList = $query->latest()->paginate(9)->withQueryString();

        return view('frontend.umkm.index', compact('profilDesa', 'umkmList', 'categories'));
    }

    /**
     * Display details of a specific active UMKM.
     */
    public function show(string $slug): View
    {
        $umkm = Umkm::where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Get other active UMKMs (max 3, exclude current)
        $umkmLainnya = Umkm::active()
            ->where('id', '!=', $umkm->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('frontend.umkm.show', compact('umkm', 'umkmLainnya'));
    }
}

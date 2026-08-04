<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Penginapan;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenginapanController extends Controller
{
    /**
     * Display a listing of published Penginapan with search and category filters.
     */
    public function index(Request $request): View
    {
        $profilDesa = ProfilDesa::first();

        $jenisList = [
            'Homestay',
            'Vila',
            'Guesthouse',
            'Rumah Warga',
            'Lainnya'
        ];

        $query = Penginapan::with('wisata')->where('is_published', true);

        // Filter by jenis / category
        if ($request->filled('jenis') && $request->jenis !== 'semua') {
            $query->where('jenis', $request->jenis);
        }

        // Search by keyword
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_penginapan', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('fasilitas_singkat', 'like', "%{$search}%")
                  ->orWhere('jarak', 'like', "%{$search}%");
            });
        }

        $penginapanList = $query->latest()->paginate(9)->withQueryString();

        return view('frontend.penginapan.index', compact('profilDesa', 'penginapanList', 'jenisList'));
    }

    /**
     * Display details of a specific published Penginapan.
     */
    public function show(int $id): View
    {
        $penginapan = Penginapan::with('wisata')
            ->where('is_published', true)
            ->findOrFail($id);

        $profilDesa = ProfilDesa::first();

        // Get other published penginapan (max 3, exclude current)
        $penginapanLainnya = Penginapan::with('wisata')
            ->where('is_published', true)
            ->where('id', '!=', $penginapan->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('frontend.penginapan.show', compact('penginapan', 'penginapanLainnya', 'profilDesa'));
    }
}

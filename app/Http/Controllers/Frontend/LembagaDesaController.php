<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LembagaDesa;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LembagaDesaController extends Controller
{
    /**
     * Display a listing of active Lembaga Desa.
     */
    public function index(Request $request): View
    {
        $profilDesa = ProfilDesa::first();

        $query = LembagaDesa::active();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lembaga', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%")
                  ->orWhere('ketua', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $lembagaList = $query->orderBy('id', 'desc')->paginate(9)->withQueryString();

        return view('frontend.lembaga.index', compact('profilDesa', 'lembagaList'));
    }

    /**
     * Display details of a specific active Lembaga Desa.
     */
    public function show(string $slug): View
    {
        $lembaga = LembagaDesa::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $profilDesa = ProfilDesa::first();

        $lembagaLainnya = LembagaDesa::active()
            ->where('id', '!=', $lembaga->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('frontend.lembaga.show', compact('lembaga', 'lembagaLainnya', 'profilDesa'));
    }
}

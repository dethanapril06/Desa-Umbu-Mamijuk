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
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $lembagaList = $query->orderBy('id', 'asc')->paginate(12)->withQueryString();

        return view('frontend.lembaga.index', compact('profilDesa', 'lembagaList'));
    }
}

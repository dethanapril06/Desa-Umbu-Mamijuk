<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KepalaDesa;
use App\Models\Penduduk;
use App\Models\PerangkatDesa;
use App\Models\ProfilDesa;
use App\Models\RtRw;
use Illuminate\View\View;

class ProfilDesaController extends Controller
{
    public function index(): View
    {
        $profilDesa = ProfilDesa::first();
        $kepalaDesa = KepalaDesa::where('is_active', true)->first();
        $perangkatDesa = PerangkatDesa::where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $totalPenduduk = Penduduk::where('status', 'aktif')->count();
        $totalRT = RtRw::distinct('no_rt')->count('no_rt');
        $totalRW = RtRw::distinct('no_rw')->count('no_rw');

        return view('frontend.profil.index', compact(
            'profilDesa',
            'kepalaDesa',
            'perangkatDesa',
            'totalPenduduk',
            'totalRT',
            'totalRW',
        ));
    }
}

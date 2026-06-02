<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\Wisata;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index(): View
    {
        $statistik = [
            'total_penduduk' => Penduduk::query()
                ->where('status', 'aktif')
                ->count(),

            'total_keluarga' => Keluarga::query()->count(),

            'total_berita' => Berita::query()->count(),

            'total_wisata' => Wisata::query()->count(),

            'total_pengaduan_masuk' => Pengaduan::query()
                ->where('status', 'masuk')
                ->count(),
        ];

        return view('admin.dashboard', compact('statistik'));
    }
}
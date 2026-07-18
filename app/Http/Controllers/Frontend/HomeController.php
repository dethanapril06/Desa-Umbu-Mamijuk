<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\KepalaDesa;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\Slider;
use App\Models\Wisata;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Profil desa (singleton)
        $profilDesa = ProfilDesa::first();

        // Kepala desa aktif
        $kepalaDesa = KepalaDesa::where('is_active', true)->first();

        // Wisata unggulan (featured = 1 item terbesar)
        $wisataUnggulan = Wisata::where('is_published', true)
            ->where('is_unggulan', true)
            ->with(['kategoriWisata'])
            ->first();

        // Wisata lainnya selain unggulan (maks 4)
        $wisataLainnya = Wisata::where('is_published', true)
            ->when($wisataUnggulan, fn ($q) => $q->where('id', '!=', $wisataUnggulan->id))
            ->with(['kategoriWisata'])
            ->latest()
            ->take(4)
            ->get();

        // Statistik kependudukan (dihitung dari data real)
        $totalPenduduk    = Penduduk::where('status', 'aktif')->count();
        $totalKK          = Keluarga::count();
        $totalLakiLaki    = Penduduk::where('status', 'aktif')->where('jenis_kelamin', 'laki-laki')->count();
        $totalPerempuan   = Penduduk::where('status', 'aktif')->where('jenis_kelamin', 'perempuan')->count();

        // Demografi usia (dihitung dari tanggal_lahir)
        $demografiUsia = $this->hitungDemografiUsia();

        // Demografi pendidikan
        $demografiPendidikan = $this->hitungDemografiPendidikan();

        // Demografi pekerjaan (top 5)
        $demografiPekerjaan = $this->hitungDemografiPekerjaan();

        // Galeri (8 foto terbaru)
        $galeri = Galeri::with('albumGaleri')
            ->latest()
            ->take(8)
            ->get();

        // Berita terbaru (3 artikel)
        $beritaTerbaru = Berita::where('is_published', true)
            ->with(['kategoriBerita'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // Sliders banner
        $sliders = Slider::where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend.home.index', compact(
            'profilDesa',
            'kepalaDesa',
            'wisataUnggulan',
            'wisataLainnya',
            'totalPenduduk',
            'totalKK',
            'totalLakiLaki',
            'totalPerempuan',
            'demografiUsia',
            'demografiPendidikan',
            'demografiPekerjaan',
            'galeri',
            'beritaTerbaru',
            'sliders',
        ));
    }

    /**
     * Menghitung distribusi usia dari data tanggal_lahir penduduk aktif.
     */
    private function hitungDemografiUsia(): array
    {
        $penduduk = Penduduk::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->get(['tanggal_lahir']);

        $total = $penduduk->count();

        if ($total === 0) {
            return [];
        }

        $kelompok = [
            '0 – 14 Tahun'  => 0,
            '15 – 29 Tahun' => 0,
            '30 – 49 Tahun' => 0,
            '50 – 64 Tahun' => 0,
            '65+ Tahun'     => 0,
        ];

        foreach ($penduduk as $p) {
            $usia = $p->tanggal_lahir->age;

            if ($usia <= 14) {
                $kelompok['0 – 14 Tahun']++;
            } elseif ($usia <= 29) {
                $kelompok['15 – 29 Tahun']++;
            } elseif ($usia <= 49) {
                $kelompok['30 – 49 Tahun']++;
            } elseif ($usia <= 64) {
                $kelompok['50 – 64 Tahun']++;
            } else {
                $kelompok['65+ Tahun']++;
            }
        }

        return array_map(fn ($jumlah) => [
            'jumlah' => $jumlah,
            'persen' => $total > 0 ? round(($jumlah / $total) * 100) : 0,
        ], $kelompok);
    }

    /**
     * Menghitung distribusi pendidikan terakhir penduduk aktif.
     */
    private function hitungDemografiPendidikan(): array
    {
        $total = Penduduk::where('status', 'aktif')->count();

        if ($total === 0) {
            return [];
        }

        $labelMap = [
            'tidak_sekolah' => 'Tidak Sekolah',
            'sd'            => 'SD / Sederajat',
            'smp'           => 'SMP / Sederajat',
            'sma'           => 'SMA / Sederajat',
            'd1'            => 'Diploma I',
            'd2'            => 'Diploma II',
            'd3'            => 'Diploma III',
            's1'            => 'S1 / Sarjana',
            's2'            => 'S2 / Magister',
            's3'            => 'S3 / Doktor',
        ];

        $data = Penduduk::where('status', 'aktif')
            ->whereNotNull('pendidikan_terakhir')
            ->selectRaw('pendidikan_terakhir, COUNT(*) as jumlah')
            ->groupBy('pendidikan_terakhir')
            ->orderByDesc('jumlah')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $label = $labelMap[$row->pendidikan_terakhir] ?? ucfirst($row->pendidikan_terakhir);
            $result[$label] = [
                'jumlah' => $row->jumlah,
                'persen' => round(($row->jumlah / $total) * 100),
            ];
        }

        return $result;
    }

    /**
     * Menghitung top 5 pekerjaan penduduk aktif.
     */
    private function hitungDemografiPekerjaan(): array
    {
        $total = Penduduk::where('status', 'aktif')->count();

        if ($total === 0) {
            return [];
        }

        $data = Penduduk::where('status', 'aktif')
            ->whereNotNull('pekerjaan')
            ->selectRaw('pekerjaan, COUNT(*) as jumlah')
            ->groupBy('pekerjaan')
            ->orderByDesc('jumlah')
            ->take(5)
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[$row->pekerjaan] = [
                'jumlah' => $row->jumlah,
                'persen' => round(($row->jumlah / $total) * 100),
            ];
        }

        return $result;
    }
}

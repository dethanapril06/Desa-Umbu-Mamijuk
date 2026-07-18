<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KependudukanController extends Controller
{
    public function index(): View
    {
        $profilDesa = ProfilDesa::first();
        $penduduk = Penduduk::where('status', 'aktif')
            ->get([
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'pendidikan_terakhir',
                'pekerjaan',
                'status_perkawinan',
            ]);

        $totalPenduduk = $penduduk->count();
        $totalKK = Keluarga::count();
        $totalLakiLaki = $penduduk->where('jenis_kelamin', 'laki-laki')->count();
        $totalPerempuan = $penduduk->where('jenis_kelamin', 'perempuan')->count();

        $ageGender = $this->ageGenderDistribution($penduduk);
        $dusunDistribution = $this->dusunDistribution();
        $pendidikanDistribution = $this->pendidikanDistribution($penduduk);
        $pekerjaanDistribution = $this->pekerjaanDistribution($penduduk);
        $wajibPilihDistribution = $this->wajibPilihDistribution($penduduk);
        $perkawinanDistribution = $this->perkawinanDistribution($penduduk);
        $agamaDistribution = $this->agamaDistribution($penduduk);

        return view('frontend.kependudukan.index', compact(
            'profilDesa',
            'totalPenduduk',
            'totalKK',
            'totalLakiLaki',
            'totalPerempuan',
            'ageGender',
            'dusunDistribution',
            'pendidikanDistribution',
            'pekerjaanDistribution',
            'wajibPilihDistribution',
            'perkawinanDistribution',
            'agamaDistribution',
        ));
    }

    private function ageGenderDistribution(Collection $penduduk): array
    {
        $groups = [
            '85+' => [85, null],
            '80-84' => [80, 84],
            '75-79' => [75, 79],
            '70-74' => [70, 74],
            '65-69' => [65, 69],
            '60-64' => [60, 64],
            '55-59' => [55, 59],
            '50-54' => [50, 54],
            '45-49' => [45, 49],
            '40-44' => [40, 44],
            '35-39' => [35, 39],
            '30-34' => [30, 34],
            '25-29' => [25, 29],
            '20-24' => [20, 24],
            '15-19' => [15, 19],
            '10-14' => [10, 14],
            '5-9' => [5, 9],
            '0-4' => [0, 4],
        ];

        $male = [];
        $female = [];

        foreach ($groups as [$min, $max]) {
            $inGroup = $penduduk->filter(function ($item) use ($min, $max) {
                if (! $item->tanggal_lahir) {
                    return false;
                }

                $age = $item->tanggal_lahir->age;

                return $max === null ? $age >= $min : ($age >= $min && $age <= $max);
            });

            $male[] = $inGroup->where('jenis_kelamin', 'laki-laki')->count();
            $female[] = $inGroup->where('jenis_kelamin', 'perempuan')->count();
        }

        return [
            'labels' => array_keys($groups),
            'male' => $male,
            'female' => $female,
        ];
    }

    private function dusunDistribution(): array
    {
        $rows = DB::table('penduduk')
            ->join('keluarga', 'penduduk.keluarga_id', '=', 'keluarga.id')
            ->join('rt_rw', 'keluarga.rt_rw_id', '=', 'rt_rw.id')
            ->join('dusun', 'rt_rw.dusun_id', '=', 'dusun.id')
            ->where('penduduk.status', 'aktif')
            ->whereNull('penduduk.deleted_at')
            ->select('dusun.nama', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('dusun.id', 'dusun.nama')
            ->orderBy('dusun.id')
            ->get();

        return [
            'labels' => $rows->pluck('nama')->values()->all(),
            'data' => $rows->pluck('jumlah')->map(fn ($value) => (int) $value)->values()->all(),
        ];
    }

    private function pendidikanDistribution(Collection $penduduk): array
    {
        $labels = [
            'tidak_sekolah' => 'Tidak Sekolah',
            'sd' => 'SD / Sederajat',
            'smp' => 'SMP / Sederajat',
            'sma' => 'SMA / Sederajat',
            'd1' => 'Diploma I',
            'd2' => 'Diploma II',
            'd3' => 'Diploma III',
            's1' => 'S1 / Sarjana',
            's2' => 'S2 / Magister',
            's3' => 'S3 / Doktor',
        ];

        $data = collect($labels)->map(fn ($label, $key) => [
            'label' => $label,
            'jumlah' => $penduduk->where('pendidikan_terakhir', $key)->count(),
        ])->filter(fn ($item) => $item['jumlah'] > 0)->values();

        return [
            'labels' => $data->pluck('label')->all(),
            'data' => $data->pluck('jumlah')->all(),
        ];
    }

    private function pekerjaanDistribution(Collection $penduduk): array
    {
        return $penduduk
            ->filter(fn ($item) => filled($item->pekerjaan))
            ->groupBy('pekerjaan')
            ->map(fn ($items, $label) => [
                'label' => $label,
                'jumlah' => $items->count(),
                'icon' => $this->pekerjaanIcon($label),
            ])
            ->sortByDesc('jumlah')
            ->values()
            ->all();
    }

    private function wajibPilihDistribution(Collection $penduduk): array
    {
        $currentYear = now()->year;
        $years = range($currentYear - 4, $currentYear);

        return [
            'labels' => $years,
            'data' => collect($years)->map(function ($year) use ($penduduk) {
                $date = Carbon::create($year, 12, 31);

                return $penduduk->filter(function ($item) use ($date) {
                    if (! $item->tanggal_lahir) {
                        return false;
                    }

                    return $item->tanggal_lahir->diffInYears($date) >= 17
                        || in_array($item->status_perkawinan, ['kawin', 'cerai_hidup', 'cerai_mati'], true);
                })->count();
            })->all(),
        ];
    }

    private function perkawinanDistribution(Collection $penduduk): array
    {
        $labels = [
            'belum_kawin' => ['label' => 'Belum Kawin', 'icon' => 'fa-user'],
            'kawin' => ['label' => 'Kawin', 'icon' => 'fa-heart'],
            'cerai_hidup' => ['label' => 'Cerai Hidup', 'icon' => 'fa-user-slash'],
            'cerai_mati' => ['label' => 'Cerai Mati', 'icon' => 'fa-ribbon'],
        ];

        return collect($labels)->map(fn ($item, $key) => [
            'label' => $item['label'],
            'icon' => $item['icon'],
            'jumlah' => $penduduk->where('status_perkawinan', $key)->count(),
        ])->all();
    }

    private function agamaDistribution(Collection $penduduk): array
    {
        $labels = [
            'islam' => ['label' => 'Islam', 'icon' => 'fa-mosque'],
            'kristen' => ['label' => 'Kristen', 'icon' => 'fa-church'],
            'katolik' => ['label' => 'Katolik', 'icon' => 'fa-cross'],
            'hindu' => ['label' => 'Hindu', 'icon' => 'fa-om'],
            'buddha' => ['label' => 'Buddha', 'icon' => 'fa-dharmachakra'],
            'konghucu' => ['label' => 'Konghucu', 'icon' => 'fa-yin-yang'],
            'lainnya' => ['label' => 'Lainnya', 'icon' => 'fa-hands-praying'],
        ];

        return collect($labels)->map(fn ($item, $key) => [
            'label' => $item['label'],
            'icon' => $item['icon'],
            'jumlah' => $penduduk->where('agama', $key)->count(),
        ])->all();
    }

    private function pekerjaanIcon(string $label): string
    {
        $text = str($label)->lower();

        return match (true) {
            $text->contains('tani') => 'fa-seedling',
            $text->contains(['dagang', 'usaha', 'wiraswasta']) => 'fa-store',
            $text->contains(['buruh', 'karyawan']) => 'fa-helmet-safety',
            $text->contains(['guru', 'dosen']) => 'fa-chalkboard-user',
            $text->contains(['pelajar', 'mahasiswa']) => 'fa-graduation-cap',
            $text->contains(['rumah tangga', 'irt']) => 'fa-house-user',
            $text->contains(['pns', 'polri', 'tni']) => 'fa-user-shield',
            $text->contains(['perawat', 'dokter', 'bidan']) => 'fa-user-nurse',
            default => 'fa-briefcase',
        };
    }
}

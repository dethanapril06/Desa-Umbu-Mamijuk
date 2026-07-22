@extends('frontend.layouts.app')

@section('title', 'Detail Kependudukan')

@section(
    'meta_description',
    'Detail statistik kependudukan ' . ($profilDesa?->nama_desa ?? 'desa') . ' berdasarkan usia, dusun, pendidikan, pekerjaan, wajib pilih, perkawinan, dan agama.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">Kependudukan</span>
            </div>
            <h1 class="page-title">Detail <em>Kependudukan</em></h1>
            <p class="page-desc">
                Ringkasan statistik penduduk {{ $profilDesa?->nama_desa ?? 'desa' }} berdasarkan usia,
                dusun, pendidikan, pekerjaan, wajib pilih, perkawinan, dan agama.
            </p>
        </div>
    </header>

    {{-- STAT CARDS --}}
    <section class="kependudukan-detail-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label" style="justify-content:center;">Ringkasan</div>
                <h2 class="section-title">Statistik <em>Utama</em></h2>
                <p class="text-muted mt-2" style="font-size:0.9rem;">
                    Data terkini per {{ now()->translatedFormat('F Y') }}
                </p>
            </div>

            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-number">{{ number_format($totalPenduduk) }} <span class="stat-unit">jiwa</span></div>
                        <div class="stat-label">Total Penduduk</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-home"></i></div>
                        <div class="stat-number">{{ number_format($totalKK) }} <span class="stat-unit">KK</span></div>
                        <div class="stat-label">Kepala Keluarga</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-male"></i></div>
                        <div class="stat-number">{{ number_format($totalLakiLaki) }} <span class="stat-unit">jiwa</span></div>
                        <div class="stat-label">Penduduk Laki-laki</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-female"></i></div>
                        <div class="stat-number">{{ number_format($totalPerempuan) }} <span class="stat-unit">jiwa</span></div>
                        <div class="stat-label">Penduduk Perempuan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- USIA DAN DUSUN --}}
    <section class="kependudukan-detail-section alt">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="chart-panel">
                        <h2 class="chart-panel-title">Berdasarkan Kelompok Umur</h2>
                        <p class="chart-panel-desc">Piramida penduduk berdasarkan kelompok umur, laki-laki, dan perempuan.</p>
                        <div class="chart-box population">
                            <canvas id="populationPyramid"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="chart-panel">
                        <h2 class="chart-panel-title">Berdasarkan Dusun</h2>
                        <p class="chart-panel-desc">Sebaran jumlah penduduk aktif pada tiap dusun.</p>
                        <div class="chart-box">
                            <canvas id="dusunPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PENDIDIKAN DAN PEKERJAAN --}}
    <section class="kependudukan-detail-section">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="chart-panel">
                        <h2 class="chart-panel-title">Berdasarkan Pendidikan</h2>
                        <p class="chart-panel-desc">Data pendidikan terakhir penduduk aktif.</p>
                        <div class="chart-box">
                            <canvas id="pendidikanBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-panel">
                <h2 class="chart-panel-title">Berdasarkan Pekerjaan</h2>
                <p class="chart-panel-desc">Daftar pekerjaan dan jumlah penduduk.</p>
                <div class="pekerjaan-layout">
                    <div class="pekerjaan-list">
                        @forelse ($pekerjaanDistribution as $item)
                            <div class="pekerjaan-list-item">
                                <div class="pekerjaan-name">{{ $item['label'] }}</div>
                                <div class="pekerjaan-count">{{ number_format($item['jumlah']) }}</div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-4">Data pekerjaan belum tersedia</div>
                        @endforelse
                    </div>

                    <div class="pekerjaan-card-grid">
                        @forelse ($pekerjaanDistribution as $item)
                            <div class="pekerjaan-card">
                                <i class="fas {{ $item['icon'] }}"></i>
                                <div class="pekerjaan-card-value">{{ number_format($item['jumlah']) }}</div>
                                <div class="pekerjaan-card-label">{{ $item['label'] }}</div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-4">Data belum tersedia</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PERKAWINAN --}}
    <section class="kependudukan-detail-section">
        <div class="container">
            <div class="mb-4">
                <div class="section-label">Status Penduduk</div>
                <h2 class="section-title">Berdasarkan <em>Perkawinan</em></h2>
            </div>
            <div class="category-card-grid">
                @foreach ($perkawinanDistribution as $item)
                    <div class="category-card">
                        <div class="category-illustration"><i class="fas {{ $item['icon'] }}"></i></div>
                        <div>
                            <div class="category-card-title">{{ $item['label'] }}</div>
                            <div class="category-card-value">{{ number_format($item['jumlah']) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AGAMA --}}
    <section class="kependudukan-detail-section alt">
        <div class="container">
            <div class="mb-4">
                <div class="section-label">Kepercayaan</div>
                <h2 class="section-title">Berdasarkan <em>Agama</em></h2>
            </div>
            <div class="category-card-grid">
                @foreach ($agamaDistribution as $item)
                    <div class="category-card">
                        <div class="category-illustration"><i class="fas {{ $item['icon'] }}"></i></div>
                        <div>
                            <div class="category-card-title">{{ $item['label'] }}</div>
                            <div class="category-card-value">{{ number_format($item['jumlah']) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const green = '#6eaa97';
        const greenDark = '#2d5a3d';
        const peach = '#ffb49c';
        const gold = '#c9a84c';
        const grid = '#dbe7ef';
        const palette = ['#52a96e', '#8dd4a0', '#c9a84c', '#2d5a3d', '#ffb49c', '#6eaa97', '#b7d7c8'];

        Chart.defaults.font.family = "'DM Sans', sans-serif";
        Chart.defaults.color = '#4a6555';

        const ageLabels = @json($ageGender['labels']);
        const male = @json($ageGender['male']);
        const female = @json($ageGender['female']);
        const pyramidMax = Math.max(10, ...male, ...female);

        new Chart(document.getElementById('populationPyramid'), {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [
                    {
                        label: 'Laki-laki',
                        data: male.map(value => -value),
                        backgroundColor: green,
                        borderRadius: 8,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Perempuan',
                        data: female,
                        backgroundColor: peach,
                        borderRadius: 8,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        min: -pyramidMax,
                        max: pyramidMax,
                        grid: { color: grid },
                        ticks: { callback: value => Math.abs(value) }
                    },
                    y: { grid: { color: grid } }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: context => `${context.dataset.label}: ${Math.abs(context.raw).toLocaleString('id-ID')}`
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('dusunPieChart'), {
            type: 'pie',
            data: {
                labels: @json($dusunDistribution['labels']),
                datasets: [{
                    data: @json($dusunDistribution['data']),
                    backgroundColor: palette,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        new Chart(document.getElementById('pendidikanBarChart'), {
            type: 'bar',
            data: {
                labels: @json($pendidikanDistribution['labels']),
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: @json($pendidikanDistribution['data']),
                    backgroundColor: greenDark,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: grid } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endpush

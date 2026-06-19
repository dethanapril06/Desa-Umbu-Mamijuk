<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/admin/login', [LoginController::class, 'store'])
        ->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile Settings
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

        // Profil Desa
        Route::get('/profil-desa', [\App\Http\Controllers\Admin\ProfilDesaController::class, 'index'])->name('profil-desa.index');
        Route::put('/profil-desa', [\App\Http\Controllers\Admin\ProfilDesaController::class, 'update'])->name('profil-desa.update');

        // Kepala Desa
        Route::patch('/kepala-desa/{kepala_desa}/toggle-status', [\App\Http\Controllers\Admin\KepalaDesaController::class, 'toggleStatus'])->name('kepala-desa.toggle-status');
        Route::resource('/kepala-desa', \App\Http\Controllers\Admin\KepalaDesaController::class);

        // Perangkat Desa
        Route::resource('/perangkat-desa', \App\Http\Controllers\Admin\PerangkatDesaController::class);

        // Slider
        Route::resource('/slider', \App\Http\Controllers\Admin\SliderController::class);

        // Sosial Media
        Route::resource('/sosial-media', \App\Http\Controllers\Admin\SosialMediaController::class)->parameters([
            'sosial-media' => 'sosialMedia'
        ]);

        // Dusun
        Route::resource('/dusun', \App\Http\Controllers\Admin\DusunController::class);

        // RT / RW
        Route::resource('/rt-rw', \App\Http\Controllers\Admin\RtRwController::class);

        // Keluarga
        Route::resource('/keluarga', \App\Http\Controllers\Admin\KeluargaController::class);

        // Penduduk
        Route::resource('/penduduk', \App\Http\Controllers\Admin\PendudukController::class);

        // Mutasi Penduduk
        Route::resource('/mutasi-penduduk', \App\Http\Controllers\Admin\MutasiPendudukController::class);

        // Kategori Berita
        Route::resource('/kategori-berita', \App\Http\Controllers\Admin\KategoriBeritaController::class)->parameters([
            'kategori-berita' => 'kategoriBerita'
        ]);

        // Tag Berita
        Route::resource('/tag', \App\Http\Controllers\Admin\TagController::class);

        // Berita
        Route::resource('/berita', \App\Http\Controllers\Admin\BeritaController::class)->parameters([
            'berita' => 'berita'
        ]);

        // Komentar Berita
        Route::patch('/komentar-berita/{komentar}/toggle-approve', [\App\Http\Controllers\Admin\KomentarBeritaController::class, 'toggleApprove'])->name('komentar-berita.toggle-approve');
        Route::resource('/komentar-berita', \App\Http\Controllers\Admin\KomentarBeritaController::class)->only(['index', 'destroy'])->parameters([
            'komentar-berita' => 'komentar'
        ]);

        // Album Galeri
        Route::resource('/album-galeri', \App\Http\Controllers\Admin\AlbumGaleriController::class);

        // Foto Galeri
        Route::resource('/galeri', \App\Http\Controllers\Admin\GaleriController::class);

        // Kategori Wisata
        Route::resource('/kategori-wisata', \App\Http\Controllers\Admin\KategoriWisataController::class)->parameters([
            'kategori-wisata' => 'kategoriWisata'
        ]);

        // Destinasi Wisata
        Route::resource('/wisata', \App\Http\Controllers\Admin\WisataController::class)->parameters([
            'wisata' => 'wisata'
        ]);

        // UMKM
        Route::resource('/umkm', \App\Http\Controllers\Admin\UmkmController::class);

        // Sub-Wisata (Fasilitas, Tips, Rute, Galeri)
        Route::post('/fasilitas-wisata', [\App\Http\Controllers\Admin\FasilitasWisataController::class, 'store'])->name('fasilitas-wisata.store');
        Route::delete('/fasilitas-wisata/{fasilitas}', [\App\Http\Controllers\Admin\FasilitasWisataController::class, 'destroy'])->name('fasilitas-wisata.destroy');

        Route::post('/tips-wisata', [\App\Http\Controllers\Admin\TipsWisataController::class, 'store'])->name('tips-wisata.store');
        Route::delete('/tips-wisata/{tips}', [\App\Http\Controllers\Admin\TipsWisataController::class, 'destroy'])->name('tips-wisata.destroy');

        Route::post('/rute-wisata', [\App\Http\Controllers\Admin\RuteWisataController::class, 'store'])->name('rute-wisata.store');
        Route::delete('/rute-wisata/{rute}', [\App\Http\Controllers\Admin\RuteWisataController::class, 'destroy'])->name('rute-wisata.destroy');

        Route::post('/galeri-wisata', [\App\Http\Controllers\Admin\GaleriWisataController::class, 'store'])->name('galeri-wisata.store');
        Route::delete('/galeri-wisata/{galeri}', [\App\Http\Controllers\Admin\GaleriWisataController::class, 'destroy'])->name('galeri-wisata.destroy');

        // Ulasan Wisata
        Route::patch('/ulasan-wisata/{ulasan}/toggle-approve', [\App\Http\Controllers\Admin\UlasanWisataController::class, 'toggleApprove'])->name('ulasan-wisata.toggle-approve');
        Route::resource('/ulasan-wisata', \App\Http\Controllers\Admin\UlasanWisataController::class)->only(['index', 'destroy'])->parameters([
            'ulasan-wisata' => 'ulasan'
        ]);

        // Kategori Pengaduan
        Route::resource('/kategori-pengaduan', \App\Http\Controllers\Admin\KategoriPengaduanController::class);

        // Pengaduan
        Route::patch('/pengaduan/{pengaduan}/status', [\App\Http\Controllers\Admin\PengaduanController::class, 'updateStatus'])->name('pengaduan.update-status');
        Route::patch('/pengaduan/{pengaduan}/toggle-publik', [\App\Http\Controllers\Admin\PengaduanController::class, 'togglePublik'])->name('pengaduan.toggle-publik');
        Route::resource('/pengaduan', \App\Http\Controllers\Admin\PengaduanController::class)->only(['index', 'show', 'destroy']);

        // Tanggapan Pengaduan
        Route::post('/tanggapan-pengaduan', [\App\Http\Controllers\Admin\TanggapanPengaduanController::class, 'store'])->name('tanggapan-pengaduan.store');
        Route::delete('/tanggapan-pengaduan/{tanggapan}', [\App\Http\Controllers\Admin\TanggapanPengaduanController::class, 'destroy'])->name('tanggapan-pengaduan.destroy');

        Route::post('/logout', [LoginController::class, 'destroy'])
            ->name('logout');
    });

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil-desa', [App\Http\Controllers\Frontend\ProfilDesaController::class, 'index'])->name('profil-desa');
Route::get('/kependudukan', [App\Http\Controllers\Frontend\KependudukanController::class, 'index'])->name('kependudukan.index');
Route::get('/galeri', [App\Http\Controllers\Frontend\GaleriController::class, 'index'])->name('galeri.index');
Route::get('/berita', [App\Http\Controllers\Frontend\BeritaController::class, 'index'])->name('berita.index');
Route::post('/berita/{slug}/komentar', [App\Http\Controllers\Frontend\BeritaController::class, 'storeKomentar'])->name('berita.komentar.store');
Route::get('/berita/{slug}', [App\Http\Controllers\Frontend\BeritaController::class, 'show'])->name('berita.show');
Route::get('/wisata', [App\Http\Controllers\Frontend\WisataController::class, 'index'])->name('wisata.index');
Route::post('/wisata/{slug}/ulasan', [App\Http\Controllers\Frontend\WisataController::class, 'storeUlasan'])->name('wisata.ulasan.store');
Route::get('/wisata/{slug}', [App\Http\Controllers\Frontend\WisataController::class, 'show'])->name('wisata.show');

Route::get('/umkm', [App\Http\Controllers\Frontend\UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{slug}', [App\Http\Controllers\Frontend\UmkmController::class, 'show'])->name('umkm.show');
Route::post('/pengaduan', [App\Http\Controllers\Frontend\PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/pengaduan/lacak/{no_tiket}', [App\Http\Controllers\Frontend\PengaduanController::class, 'track'])->name('pengaduan.track');

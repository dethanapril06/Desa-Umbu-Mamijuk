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
Route::post('/pengaduan', [App\Http\Controllers\Frontend\PengaduanController::class, 'store'])->name('pengaduan.store');

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Wisata;
use App\Models\Umkm;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $beritaList = Berita::where('is_published', true)
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $wisataList = Wisata::where('is_published', true)
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $umkmList = Umkm::where('status', 'aktif')
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('frontend.sitemap', compact('beritaList', 'wisataList', 'umkmList'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}

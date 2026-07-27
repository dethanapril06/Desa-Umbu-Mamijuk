<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PanduanController extends Controller
{
    /**
     * Langsung mengunduh file PDF (.pdf) Buku Panduan Administrator.
     * Cek di beberapa lokasi penyimpanan agar fleksibel saat admin/user mengunggah file editan baru.
     */
    public function index()
    {
        $possiblePaths = [
            public_path('Buku_Panduan_Administrator_Website_Desa_Umbu_Mamijuk.pdf'),
            public_path('docs/Buku_Panduan_Administrator_Website_Desa_Umbu_Mamijuk.pdf'),
            base_path('Buku_Panduan_Administrator_Website_Desa_Umbu_Mamijuk.pdf')
        ];

        foreach ($possiblePaths as $path) {
            if (File::exists($path)) {
                return response()->download($path, 'Buku_Panduan_Administrator_Website_Desa_Umbu_Mamijuk.pdf');
            }
        }

        return back()->with('error', 'File Buku Panduan (.pdf) saat ini sedang diperbarui. Harap hubungi administrator/pengembang.');
    }
}

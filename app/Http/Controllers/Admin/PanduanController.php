<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PanduanController extends Controller
{
    /**
     * Langsung mengunduh file Word (.docx) Buku Panduan Administrator.
     * Cek di beberapa lokasi penyimpanan agar fleksibel saat admin/user mengunggah file editan baru.
     */
    public function index()
    {
        $possiblePaths = [
            public_path('Buku_Panduan_Administrator_WebDesaIbuAdri.docx'),
            public_path('docs/Buku_Panduan_Administrator_WebDesaIbuAdri.docx'),
            base_path('Buku_Panduan_Administrator_WebDesaIbuAdri.docx')
        ];

        foreach ($possiblePaths as $path) {
            if (File::exists($path)) {
                return response()->download($path, 'Buku_Panduan_Administrator_WebDesaIbuAdri.docx');
            }
        }

        return back()->with('error', 'File Buku Panduan (.docx) saat ini sedang diperbarui. Harap hubungi administrator/pengembang.');
    }
}

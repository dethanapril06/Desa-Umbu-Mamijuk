<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KepalaDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KepalaDesaController extends Controller
{
    public function index(): View
    {
        $kepalaDesaList = KepalaDesa::orderBy('id', 'desc')->paginate(10);
        return view('admin.kepala-desa.index', compact('kepalaDesaList'));
    }

    public function create(): View
    {
        return view('admin.kepala-desa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
            'periode_mulai' => 'required|string|max:50',
            'periode_selesai' => 'required|string|max:50',
            'sambutan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=250,min_height=300',
            'is_active' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama Kepala Desa wajib diisi.',
            'periode_mulai.required' => 'Tahun periode mulai wajib diisi.',
            'periode_selesai.required' => 'Tahun periode selesai wajib diisi.',
            'foto.dimensions' => 'Resolusi foto terlalu kecil! Minimal lebar 250px dan tinggi 300px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/kepala-desa', 'public');
            $data['foto'] = $path;
        }

        if ($data['is_active']) {
            // Deactivate all others
            KepalaDesa::query()->update(['is_active' => false]);
        }

        KepalaDesa::create($data);

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Data kepala desa berhasil ditambahkan!');
    }

    public function edit(KepalaDesa $kepalaDesa): View
    {
        return view('admin.kepala-desa.edit', compact('kepalaDesa'));
    }

    public function update(Request $request, KepalaDesa $kepalaDesa): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
            'periode_mulai' => 'required|string|max:50',
            'periode_selesai' => 'required|string|max:50',
            'sambutan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=250,min_height=300',
            'is_active' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama Kepala Desa wajib diisi.',
            'periode_mulai.required' => 'Tahun periode mulai wajib diisi.',
            'periode_selesai.required' => 'Tahun periode selesai wajib diisi.',
            'foto.dimensions' => 'Resolusi foto terlalu kecil! Minimal lebar 250px dan tinggi 300px.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['foto']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('foto')) {
            if ($kepalaDesa->foto && Storage::disk('public')->exists($kepalaDesa->foto)) {
                Storage::disk('public')->delete($kepalaDesa->foto);
            }
            $path = $request->file('foto')->store('images/kepala-desa', 'public');
            $data['foto'] = $path;
        }

        if ($data['is_active']) {
            // Deactivate all others
            KepalaDesa::where('id', '!=', $kepalaDesa->id)->update(['is_active' => false]);
        }

        $kepalaDesa->update($data);

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Data kepala desa berhasil diperbarui!');
    }

    public function destroy(KepalaDesa $kepalaDesa): RedirectResponse
    {
        if ($kepalaDesa->foto && Storage::disk('public')->exists($kepalaDesa->foto)) {
            Storage::disk('public')->delete($kepalaDesa->foto);
        }
        $kepalaDesa->delete();

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Data kepala desa berhasil dihapus!');
    }

    /**
     * Tampilkan detail atau status kepala desa
     */
    public function toggleStatus(KepalaDesa $kepalaDesa): RedirectResponse
    {
        $newStatus = !$kepalaDesa->is_active;

        if ($newStatus) {
            KepalaDesa::query()->update(['is_active' => false]);
        }

        $kepalaDesa->update(['is_active' => $newStatus]);

        return redirect()->route('admin.kepala-desa.index')->with('success', 'Status keaktifan kepala desa berhasil diubah!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        // 1. Bersihkan spasi berlebih pada field baris tunggal & Capital Each Word khusus untuk 'nama'
        $singleFields = ['nama', 'gelar', 'periode_mulai', 'periode_selesai'];
        foreach ($singleFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                if ($field === 'nama') {
                    $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                }
                $request->merge([$field => $cleaned]);
            }
        }

        // 2. Bersihkan spasi berlebih & format Sentence case pada sambutan (multiline safe)
        if ($request->has('sambutan') && is_string($request->input('sambutan')) && !empty($request->input('sambutan'))) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('sambutan'));
            $cleanedLines = [];
            foreach ($lines as $line) {
                $cleanedLines[] = preg_replace('/[^\S\r\n]+/', ' ', trim($line));
            }
            $text = implode("\n", $cleanedLines);
            $text = preg_replace('/\n{3,}/', "\n\n", trim($text));

            // Format Sentence case (huruf pertama di setiap kalimat / paragraf menjadi kapital)
            $text = mb_strtolower($text, 'UTF-8');
            $text = preg_replace_callback('/(^|[.!?]\s+|\r?\n+)([a-z\p{L}])/u', function ($matches) {
                return $matches[1] . mb_strtoupper($matches[2], 'UTF-8');
            }, $text);

            $request->merge(['sambutan' => $text]);
        }
    }
}

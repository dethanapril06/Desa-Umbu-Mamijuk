<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LembagaDesa;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LembagaDesaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = LembagaDesa::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lembaga', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%")
                  ->orWhere('ketua', 'like', "%{$search}%")
                  ->orWhere('alamat_sekretariat', 'like', "%{$search}%");
            });
        }

        $lembagaList = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.lembaga-desa.index', compact('lembagaList', 'search'));
    }

    public function create(): View
    {
        return view('admin.lembaga-desa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama_lembaga' => 'required|string|max:255|unique:lembaga_desa,nama_lembaga',
            'singkatan' => 'nullable|string|max:100',
            'ketua' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'alamat_sekretariat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'nama_lembaga.required' => 'Nama lembaga desa wajib diisi.',
            'nama_lembaga.unique' => 'Nama lembaga desa tersebut sudah terdaftar.',
            'logo.max' => 'Ukuran file logo maksimal 10 MB.',
            'logo.mimes' => 'Format logo harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['logo']);
        $data['slug'] = Str::slug($request->nama_lembaga);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('logo')) {
            $path = ImageService::processAndStore($request->file('logo'), 'images/lembaga-desa');
            $data['logo'] = $path;
        }

        LembagaDesa::create($data);

        return redirect()->route('admin.lembaga-desa.index')->with('success', 'Lembaga desa berhasil ditambahkan!');
    }

    public function edit(LembagaDesa $lembagaDesa): View
    {
        return view('admin.lembaga-desa.edit', compact('lembagaDesa'));
    }

    public function update(Request $request, LembagaDesa $lembagaDesa): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'nama_lembaga' => 'required|string|max:255|unique:lembaga_desa,nama_lembaga,' . $lembagaDesa->id,
            'singkatan' => 'nullable|string|max:100',
            'ketua' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'alamat_sekretariat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'nama_lembaga.required' => 'Nama lembaga desa wajib diisi.',
            'nama_lembaga.unique' => 'Nama lembaga desa tersebut sudah terdaftar.',
            'logo.max' => 'Ukuran file logo maksimal 10 MB.',
            'logo.mimes' => 'Format logo harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        $data = $request->except(['logo']);
        $data['slug'] = Str::slug($request->nama_lembaga);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        if ($request->hasFile('logo')) {
            if ($lembagaDesa->logo && Storage::disk('public')->exists($lembagaDesa->logo)) {
                Storage::disk('public')->delete($lembagaDesa->logo);
            }
            $path = ImageService::processAndStore($request->file('logo'), 'images/lembaga-desa');
            $data['logo'] = $path;
        }

        $lembagaDesa->update($data);

        return redirect()->route('admin.lembaga-desa.index')->with('success', 'Lembaga desa berhasil diperbarui!');
    }

    public function deleteLogo(LembagaDesa $lembagaDesa): RedirectResponse
    {
        if ($lembagaDesa->logo && Storage::disk('public')->exists($lembagaDesa->logo)) {
            Storage::disk('public')->delete($lembagaDesa->logo);
        }
        $lembagaDesa->update(['logo' => null]);

        return redirect()->back()->with('success', 'Logo lembaga desa berhasil dihapus!');
    }

    public function toggleStatus(LembagaDesa $lembagaDesa): RedirectResponse
    {
        $lembagaDesa->update(['is_active' => !$lembagaDesa->is_active]);

        $statusMsg = $lembagaDesa->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Lembaga desa berhasil {$statusMsg}!");
    }

    public function destroy(LembagaDesa $lembagaDesa): RedirectResponse
    {
        if ($lembagaDesa->logo && Storage::disk('public')->exists($lembagaDesa->logo)) {
            Storage::disk('public')->delete($lembagaDesa->logo);
        }
        $lembagaDesa->delete();

        return redirect()->route('admin.lembaga-desa.index')->with('success', 'Lembaga desa berhasil dihapus!');
    }

    /**
     * Normalisasi input string.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('nama_lembaga') && is_string($request->input('nama_lembaga')) && !empty($request->input('nama_lembaga'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('nama_lembaga')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['nama_lembaga' => $cleaned]);
        }

        if ($request->has('ketua') && is_string($request->input('ketua')) && !empty($request->input('ketua'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('ketua')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['ketua' => $cleaned]);
        }
    }
}

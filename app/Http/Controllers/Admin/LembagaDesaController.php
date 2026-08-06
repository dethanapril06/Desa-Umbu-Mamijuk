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
            'ketua' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:50',
            'alamat_sekretariat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'nama_lembaga.required' => 'Nama lembaga desa wajib diisi.',
            'nama_lembaga.unique' => 'Nama lembaga desa tersebut sudah terdaftar.',
            'ketua.required' => 'Nama ketua / penanggung jawab wajib diisi.',
            'no_telepon.required' => 'No. telepon / kontak sekretariat wajib diisi.',
            'alamat_sekretariat.required' => 'Alamat sekretariat / kantor wajib diisi.',
            'deskripsi.required' => 'Deskripsi & tugas lembaga wajib diisi.',
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
            'ketua' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:50',
            'alamat_sekretariat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'nama_lembaga.required' => 'Nama lembaga desa wajib diisi.',
            'nama_lembaga.unique' => 'Nama lembaga desa tersebut sudah terdaftar.',
            'ketua.required' => 'Nama ketua / penanggung jawab wajib diisi.',
            'no_telepon.required' => 'No. telepon / kontak sekretariat wajib diisi.',
            'alamat_sekretariat.required' => 'Alamat sekretariat / kantor wajib diisi.',
            'deskripsi.required' => 'Deskripsi & tugas lembaga wajib diisi.',
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
        $titleFields = ['nama_lembaga', 'ketua', 'alamat_sekretariat'];
        foreach ($titleFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $request->merge([$field => $cleaned]);
            }
        }

        if ($request->has('singkatan') && is_string($request->input('singkatan')) && !empty($request->input('singkatan'))) {
            $cleaned = preg_replace('/\s+/', '', trim($request->input('singkatan')));
            $request->merge(['singkatan' => mb_strtoupper($cleaned, 'UTF-8')]);
        }
    }
}

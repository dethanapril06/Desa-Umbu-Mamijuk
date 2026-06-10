<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfilDesaController extends Controller
{
    /**
     * Tampilkan form profil desa.
     */
    public function index(): View
    {
        $profil = ProfilDesa::first();
        if (!$profil) {
            $profil = ProfilDesa::create([
                'nama_desa' => 'Umbu Mamijuk',
                'kecamatan' => 'Umbu Ratu Nggay Barat',
                'kabupaten' => 'Sumba Tengah',
                'provinsi' => 'Nusa Tenggara Timur',
            ]);
        }
        return view('admin.profil-desa.index', compact('profil'));
    }

    /**
     * Update profil desa.
     */
    public function update(Request $request): RedirectResponse
    {
        $profil = ProfilDesa::first();
        if (!$profil) {
            $profil = new ProfilDesa();
        }

        $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat_lengkap' => 'nullable|string',
            'luas_wilayah' => 'nullable|string|max:50',
            'ketinggian' => 'nullable|string|max:50',
            'jam_pelayanan' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah_desa' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'batas_utara' => 'nullable|string|max:255',
            'batas_timur' => 'nullable|string|max:255',
            'batas_selatan' => 'nullable|string|max:255',
            'batas_barat' => 'nullable|string|max:255',
            'koordinat_lat' => 'nullable|string|max:50',
            'koordinat_lng' => 'nullable|string|max:50',
            'gambar_struktur_organisasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['logo', 'gambar_struktur_organisasi']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }
            $logoPath = $request->file('logo')->store('images/profil', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle gambar struktur organisasi
        if ($request->hasFile('gambar_struktur_organisasi')) {
            if ($profil->gambar_struktur_organisasi && Storage::disk('public')->exists($profil->gambar_struktur_organisasi)) {
                Storage::disk('public')->delete($profil->gambar_struktur_organisasi);
            }
            $strukturPath = $request->file('gambar_struktur_organisasi')->store('images/profil', 'public');
            $data['gambar_struktur_organisasi'] = $strukturPath;
        }

        $profil->fill($data);
        $profil->save();

        return redirect()->route('admin.profil-desa.index')->with('success', 'Profil desa berhasil diperbarui!');
    }
}

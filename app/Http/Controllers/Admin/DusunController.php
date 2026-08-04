<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DusunController extends Controller
{
    public function index(): View
    {
        $dusunList = Dusun::orderBy('id', 'asc')->paginate(10);
        return view('admin.dusun.index', compact('dusunList'));
    }

    public function create(): View
    {
        return view('admin.dusun.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:dusun,nama',
            'kepala_dusun' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ];

        $messages = [
            'nama.required' => 'Nama dusun wajib diisi.',
            'nama.unique' => 'Nama dusun tersebut sudah terdaftar di sistem.',
            'kepala_dusun.required' => 'Kepala dusun wajib diisi.',
        ];

        $request->validate($rules, $messages);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        Dusun::create($data);

        return redirect()->route('admin.dusun.index')->with('success', 'Data dusun berhasil ditambahkan!');
    }

    public function edit(Dusun $dusun): View
    {
        return view('admin.dusun.edit', compact('dusun'));
    }

    public function update(Request $request, Dusun $dusun): RedirectResponse
    {
        $this->normalizeInput($request);

        $rules = [
            'nama' => 'required|string|max:255|unique:dusun,nama,' . $dusun->id,
            'kepala_dusun' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ];

        $messages = [
            'nama.required' => 'Nama dusun wajib diisi.',
            'nama.unique' => 'Nama dusun tersebut sudah terdaftar di sistem.',
            'kepala_dusun.required' => 'Kepala dusun wajib diisi.',
        ];

        $request->validate($rules, $messages);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $dusun->update($data);

        return redirect()->route('admin.dusun.index')->with('success', 'Data dusun berhasil diperbarui!');
    }

    public function destroy(Dusun $dusun): RedirectResponse
    {
        // Check if there are RT/RW under this dusun
        if ($dusun->rtRw()->count() > 0) {
            return redirect()->route('admin.dusun.index')->with('error', 'Tidak dapat menghapus dusun ini karena memiliki data RT/RW!');
        }

        $dusun->delete();
        return redirect()->route('admin.dusun.index')->with('success', 'Data dusun berhasil dihapus!');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        $fields = ['nama', 'kepala_dusun'];
        foreach ($fields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = preg_replace('/\s+/', ' ', trim($request->input($field)));
                $cleaned = $this->toCapitalEachWord($cleaned);
                $request->merge([$field => $cleaned]);
            }
        }
    }

    private function toCapitalEachWord(string $str): string
    {
        $romanPattern = '/^(?:M{0,4}(?:CM|CD|D?C{0,3})(?:XC|XL|L?X{0,3})(?:IX|IV|V?I{0,3}))$/i';
        return preg_replace_callback('/\b[a-zA-Z]+\b/', function ($matches) use ($romanPattern) {
            $word = $matches[0];
            if (preg_match($romanPattern, $word)) {
                return strtoupper($word);
            }
            return mb_convert_case(mb_strtolower($word, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
        }, $str);
    }
}

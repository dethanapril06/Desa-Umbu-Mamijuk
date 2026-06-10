<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UlasanWisata;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UlasanWisataController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $rating = $request->input('rating');

        $query = UlasanWisata::with('wisata');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('ulasan', 'like', "%{$search}%")
                  ->orWhereHas('wisata', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
        }

        if ($rating) {
            $query->where('rating', $rating);
        }

        $ulasanList = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.ulasan-wisata.index', compact('ulasanList', 'search', 'rating'));
    }

    public function toggleApprove(UlasanWisata $ulasan): RedirectResponse
    {
        $ulasan->is_approved = !$ulasan->is_approved;
        $ulasan->save();

        $statusText = $ulasan->is_approved ? 'disetujui' : 'ditolak/disembunyikan';
        return redirect()->route('admin.ulasan-wisata.index')->with('success', "Ulasan dari {$ulasan->nama} berhasil {$statusText}!");
    }

    public function destroy(UlasanWisata $ulasan): RedirectResponse
    {
        $name = $ulasan->nama;
        $ulasan->delete();

        return redirect()->route('admin.ulasan-wisata.index')->with('success', "Ulasan dari {$name} berhasil dihapus!");
    }
}

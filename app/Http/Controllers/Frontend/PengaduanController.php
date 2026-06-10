<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengaduanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_pengaduan_id' => 'required|exists:kategori_pengaduan,id',
            'nama_pelapor'          => 'required|string|max:255',
            'nik_pelapor'           => 'required|string|size:16',
            'no_telepon'            => 'required|string|max:20',
            'email'                 => 'nullable|email|max:255',
            'alamat'                => 'required|string',
            'judul'                 => 'required|string|max:255',
            'isi_pengaduan'         => 'required|string',
            'lampiran'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_publik'             => 'nullable|boolean',
        ], [
            'kategori_pengaduan_id.required' => 'Kategori pengaduan wajib dipilih.',
            'kategori_pengaduan_id.exists'   => 'Kategori pengaduan tidak valid.',
            'nama_pelapor.required'          => 'Nama lengkap pelapor wajib diisi.',
            'nik_pelapor.required'           => 'NIK pelapor wajib diisi.',
            'nik_pelapor.size'               => 'NIK harus berjumlah 16 digit.',
            'no_telepon.required'            => 'Nomor telepon aktif wajib diisi.',
            'alamat.required'                => 'Alamat pelapor wajib diisi.',
            'judul.required'                 => 'Judul pengaduan wajib diisi.',
            'isi_pengaduan.required'         => 'Detail isi laporan pengaduan wajib diisi.',
            'lampiran.image'                 => 'Berkas lampiran harus berupa gambar.',
            'lampiran.mimes'                 => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'lampiran.max'                   => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->except(['lampiran']);
        
        // Handle file upload
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('pengaduan', 'public');
            $data['lampiran'] = $path;
        }

        // Generate Ticket Number: ADU-YYYY-XXXX
        $currentYear = date('Y');
        $count = Pengaduan::whereYear('created_at', $currentYear)->count() + 1;
        $data['no_tiket'] = 'ADU-' . $currentYear . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        
        // Defaults
        $data['status'] = 'masuk';
        $data['is_publik'] = $request->has('is_publik') ? (bool) $request->input('is_publik') : false;

        $pengaduan = Pengaduan::create($data);

        return response()->json([
            'success'  => true,
            'message'  => 'Pengaduan Anda berhasil dikirim!',
            'no_tiket' => $pengaduan->no_tiket
        ]);
    }

    public function track($no_tiket)
    {
        $pengaduan = Pengaduan::with(['kategoriPengaduan', 'tanggapanPengaduan' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])->where('no_tiket', $no_tiket)->first();

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor tiket tidak ditemukan. Pastikan format penulisan benar (contoh: ADU-2026-0001).'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'no_tiket' => $pengaduan->no_tiket,
                'nama_pelapor' => $pengaduan->nama_pelapor,
                'judul' => $pengaduan->judul,
                'isi_pengaduan' => $pengaduan->isi_pengaduan,
                'tanggal' => $pengaduan->created_at->translatedFormat('d F Y H:i'),
                'kategori' => $pengaduan->kategoriPengaduan?->nama ?? 'Umum',
                'status' => $pengaduan->status,
                'lampiran' => $pengaduan->lampiran ? asset('storage/' . $pengaduan->lampiran) : null,
                'tanggapan' => $pengaduan->tanggapanPengaduan->map(function ($t) {
                    return [
                        'petugas' => 'Petugas Desa',
                        'isi_tanggapan' => $t->isi_tanggapan,
                        'tanggal' => $t->created_at->translatedFormat('d M Y H:i'),
                        'lampiran' => $t->lampiran ? asset('storage/' . $t->lampiran) : null,
                    ];
                })
            ]
        ]);
    }
}

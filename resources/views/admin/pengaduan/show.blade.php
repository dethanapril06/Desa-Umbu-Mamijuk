@extends('admin.layouts.app')

@section('title', 'Detail Pengaduan - ' . $pengaduan->no_tiket)

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pelayanan Publik / <a href="{{ route('admin.pengaduan.index') }}">Daftar Pengaduan</a> /</span> Detail
        </h4>

        <!-- Main actions row -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Nomor Tiket: <span class="badge bg-label-primary fs-6">{{ $pengaduan->no_tiket }}</span></h5>
            <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <!-- Left Side: Detail & Pelapor -->
            <div class="col-12 col-lg-8">
                <!-- Complaint Content Card -->
                <div class="card mb-4 border-start border-primary border-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-label-dark">
                                @if($pengaduan->kategoriPengaduan && $pengaduan->kategoriPengaduan->icon)
                                    <i class="bx {{ $pengaduan->kategoriPengaduan->icon }} me-1"></i>
                                @endif
                                {{ $pengaduan->kategoriPengaduan->nama ?? 'Tanpa Kategori' }}
                            </span>
                            <span class="text-muted fs-7">Masuk: {{ $pengaduan->created_at->translatedFormat('d F Y H:i') }}</span>
                        </div>

                        <h3 class="card-title fw-bold text-primary mb-3">{{ $pengaduan->judul }}</h3>
                        
                        <div class="p-3 bg-light rounded mb-4" style="white-space: pre-wrap; line-height: 1.6;">{{ $pengaduan->isi_pengaduan }}</div>

                        <!-- Attachment Section -->
                        <h6 class="fw-bold"><i class="bx bx-paperclip me-1"></i> Lampiran Laporan</h6>
                        <div class="border rounded p-3 bg-light d-flex align-items-center">
                            @if($pengaduan->lampiran)
                                @php
                                    $ext = pathinfo($pengaduan->lampiran, PATHINFO_EXTENSION);
                                    $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                @endphp
                                @if($isImg)
                                    <div class="row align-items-center w-100">
                                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                                            <a href="{{ asset('storage/' . $pengaduan->lampiran) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $pengaduan->lampiran) }}" class="img-fluid rounded border hover-shadow" style="max-height: 150px; object-fit: cover;" alt="Lampiran Pengaduan">
                                            </a>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <p class="mb-1 text-muted fs-7">Format: {{ strtoupper($ext) }}</p>
                                            <a href="{{ asset('storage/' . $pengaduan->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-fullscreen me-1"></i> Lihat Gambar Ukuran Penuh
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-file fs-1 text-primary me-2"></i>
                                            <div>
                                                <span class="fw-bold">Berkas Lampiran</span>
                                                <p class="mb-0 text-muted fs-7">Format: {{ strtoupper($ext) }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $pengaduan->lampiran) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bx bx-download me-1"></i> Unduh File
                                        </a>
                                    </div>
                                @endif
                            @else
                                <span class="text-muted"><i class="bx bx-info-circle me-1"></i> Pelapor tidak melampirkan berkas gambar/dokumen.</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reporter Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-label-secondary">
                        <h5 class="mb-0 fw-bold"><i class="bx bx-user me-1"></i> Informasi Identitas Pelapor</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <span class="text-muted d-block fs-7">Nama Lengkap</span>
                                <span class="fw-bold text-dark fs-6">{{ $pengaduan->nama_pelapor }}</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted d-block fs-7">NIK (Nomor Induk Kependudukan)</span>
                                <span class="fw-bold text-dark fs-6">{{ $pengaduan->nik_pelapor ?? '-' }}</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted d-block fs-7">Nomor Telepon / WhatsApp</span>
                                <span class="fw-bold text-dark fs-6">
                                    @if($pengaduan->no_telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengaduan->no_telepon) }}" target="_blank" class="text-success">
                                            <i class="bx bxl-whatsapp me-1"></i>{{ $pengaduan->no_telepon }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted d-block fs-7">Alamat Email</span>
                                <span class="fw-bold text-dark fs-6">
                                    @if($pengaduan->email)
                                        <a href="mailto:{{ $pengaduan->email }}">{{ $pengaduan->email }}</a>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="col-12">
                                <span class="text-muted d-block fs-7">Alamat Lengkap</span>
                                <span class="fw-bold text-dark fs-6">{{ $pengaduan->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responses Timeline Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bx bx-history me-1"></i> Riwayat Tindak Lanjut & Tanggapan</h5>
                        <span class="badge bg-primary">{{ $pengaduan->tanggapanPengaduan->count() }} Tanggapan</span>
                    </div>
                    <div class="card-body">
                        @forelse($pengaduan->tanggapanPengaduan as $tanggapan)
                            <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        <i class="bx bx-user-circle fs-3"></i>
                                    </span>
                                </div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <span class="fw-bold text-dark">{{ $tanggapan->user->name ?? 'Admin' }}</span>
                                            <span class="badge bg-label-info ms-2 fs-8">Petugas</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-2">{{ $tanggapan->created_at->translatedFormat('d M Y H:i') }}</small>
                                            
                                            <!-- Delete response form -->
                                            <form action="{{ route('admin.tanggapan-pengaduan.destroy', $tanggapan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanggapan ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs p-0 text-danger" title="Hapus Tanggapan">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="text-wrap bg-light p-2 rounded mb-2 fs-6 text-dark" style="white-space: pre-wrap;">{{ $tanggapan->isi_tanggapan }}</div>
                                    
                                    <!-- Tanggapan Attachment -->
                                    @if($tanggapan->lampiran)
                                        @php
                                            $tExt = pathinfo($tanggapan->lampiran, PATHINFO_EXTENSION);
                                            $tIsImg = in_array(strtolower($tExt), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                        @endphp
                                        <div class="d-inline-block p-2 rounded border bg-white mt-1 mb-2">
                                            @if($tIsImg)
                                                <a href="{{ asset('storage/' . $tanggapan->lampiran) }}" target="_blank" class="d-flex align-items-center text-secondary">
                                                    <i class="bx bx-image me-1"></i>
                                                    <small>Lihat Lampiran ({{ strtoupper($tExt) }})</small>
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $tanggapan->lampiran) }}" target="_blank" class="d-flex align-items-center text-primary">
                                                    <i class="bx bx-file-blank me-1"></i>
                                                    <small>Unduh Dokumen ({{ strtoupper($tExt) }})</small>
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- WhatsApp Notification Button -->
                                    @if($pengaduan->no_telepon)
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $pengaduan->no_telepon);
                                            if (strpos($cleanPhone, '0') === 0) {
                                                $cleanPhone = '62' . substr($cleanPhone, 1);
                                            }
                                            
                                            $waMessage = "Halo *" . $pengaduan->nama_pelapor . "*,\n\n"
                                                       . "Terkait laporan Anda dengan No. Tiket *" . $pengaduan->no_tiket . "* (Judul: *" . $pengaduan->judul . "*), berikut adalah tanggapan terbaru kami:\n\n"
                                                       . "\"" . $tanggapan->isi_tanggapan . "\"\n\n"
                                                       . "Status Laporan Anda saat ini: *" . strtoupper($pengaduan->status == 'diproses' ? 'diproses' : $pengaduan->status) . "*\n"
                                                       . "Anda dapat melacak status laporan secara berkala pada widget pengaduan di website desa.\n\n"
                                                       . "Terima kasih.";
                                        @endphp
                                        <div class="mt-1">
                                            <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}&text={{ rawurlencode($waMessage) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                <i class="bx bxl-whatsapp me-1"></i> Kirim Notifikasi via WhatsApp
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bx bx-message-rounded-x fs-1 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada tanggapan atau tindakan untuk pengaduan ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Side: Status Updates & Response Input -->
            <div class="col-12 col-lg-4">
                <!-- Status Box -->
                <div class="card mb-4 bg-label-dark border-top border-primary border-3">
                    <div class="card-body">
                        <span class="text-muted d-block mb-1">Status Laporan & Akses</span>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            @if($pengaduan->status === 'masuk')
                                <span class="badge bg-info fs-6 px-2.5 py-2">Masuk</span>
                            @elseif($pengaduan->status === 'diproses')
                                <span class="badge bg-warning fs-6 px-2.5 py-2 text-dark">Diproses</span>
                            @elseif($pengaduan->status === 'selesai')
                                <span class="badge bg-success fs-6 px-2.5 py-2">Selesai</span>
                            @elseif($pengaduan->status === 'ditolak')
                                <span class="badge bg-danger fs-6 px-2.5 py-2">Ditolak</span>
                            @endif

                            @if($pengaduan->is_publik)
                                <span class="badge bg-success fs-6 px-2.5 py-2" title="Laporan dipublikasikan di halaman desa"><i class="bx bx-check-shield me-1"></i>Publik</span>
                            @else
                                <span class="badge bg-secondary fs-6 px-2.5 py-2" title="Laporan bersifat rahasia/internal"><i class="bx bx-lock-open-alt me-1"></i>Privat</span>
                            @endif
                        </div>

                        <!-- Toggle Publik Form -->
                        <form action="{{ route('admin.pengaduan.toggle-publik', $pengaduan->id) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                @if($pengaduan->is_publik)
                                    <i class="bx bx-lock-alt me-1"></i> Ubah Menjadi Privat
                                @else
                                    <i class="bx bx-world me-1"></i> Publikasikan Laporan
                                @endif
                            </button>
                        </form>
                        
                        <hr class="my-3">

                        <!-- Update Status Form -->
                        <form action="{{ route('admin.pengaduan.update-status', $pengaduan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold" for="status">Ubah Status Pengaduan</label>
                                <select id="status" name="status" class="form-select border-primary" required>
                                    @foreach($statusOptions as $opt)
                                        <option value="{{ $opt }}" {{ $pengaduan->status == $opt ? 'selected' : '' }}>
                                            {{ ucfirst($opt == 'diproses' ? 'diproses' : $opt) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-check-double me-1"></i> Perbarui Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Add Response Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white fw-bold"><i class="bx bx-message-square-detail me-1"></i> Tulis Tanggapan</h5>
                    </div>
                    <div class="card-body pt-3">
                        <form action="{{ route('admin.tanggapan-pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="pengaduan_id" value="{{ $pengaduan->id }}">

                            <div class="mb-3">
                                <label class="form-label" for="isi_tanggapan">Isi Tanggapan/Balasan <span class="text-danger">*</span></label>
                                <textarea 
                                    id="isi_tanggapan" 
                                    name="isi_tanggapan" 
                                    rows="5" 
                                    class="form-control" 
                                    placeholder="Ketik rincian tanggapan, penjelasan, atau solusi untuk laporan ini..." 
                                    required>{{ old('isi_tanggapan') }}</textarea>
                                <div class="form-text fs-8 text-muted">Bila status pengaduan masih 'masuk', mengirim tanggapan akan otomatis mengubah status menjadi 'diproses'.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="lampiran">Lampiran Pendukung (Opsional)</label>
                                <input type="file" class="form-control" id="lampiran" name="lampiran">
                                <div class="form-text fs-8">Gambar atau berkas pendukung (PDF/Doc/XLS/Image max 2MB).</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-send me-1"></i> Kirim Tanggapan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

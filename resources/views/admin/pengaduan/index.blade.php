@extends('admin.layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pelayanan Publik /</span> Daftar Pengaduan
        </h4>

        <!-- Filter & Search Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.pengaduan.index') }}" class="row g-3 align-items-center">
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="search">Cari</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                placeholder="Cari No Tiket, Nama, Judul..."
                                value="{{ request('search') }}"
                            />
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statusOptions as $opt)
                                <option value="{{ $opt }}" {{ request('status') == $opt ? 'selected' : '' }}>
                                    {{ ucfirst($opt == 'diproses' ? 'diproses' : $opt) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="kategori_pengaduan_id">Kategori</label>
                        <select id="kategori_pengaduan_id" name="kategori_pengaduan_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_pengaduan_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-grid pt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Complaints Table Card -->
        <div class="card">
            <h5 class="card-header">Daftar Laporan Pengaduan</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No Tiket</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Judul Pengaduan</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Publik?</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($pengaduanList as $pengaduan)
                            <tr>
                                <td>
                                    <span class="badge bg-label-secondary fw-bold">{{ $pengaduan->no_tiket }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $pengaduan->nama_pelapor }}</span>
                                        <small class="text-muted">NIK: {{ $pengaduan->nik_pelapor ?? '-' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($pengaduan->kategoriPengaduan)
                                        <span class="badge bg-label-dark">
                                            @if($pengaduan->kategoriPengaduan->icon)
                                                <i class="bx {{ $pengaduan->kategoriPengaduan->icon }} me-1"></i>
                                            @endif
                                            {{ $pengaduan->kategoriPengaduan->nama }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-wrap" style="max-width: 250px; display: inline-block;">
                                        {{ Str::limit($pengaduan->judul, 45) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $pengaduan->created_at->translatedFormat('d M Y H:i') }}
                                </td>
                                <td>
                                    @if($pengaduan->status === 'masuk')
                                        <span class="badge bg-label-info">Masuk</span>
                                    @elseif($pengaduan->status === 'diproses')
                                        <span class="badge bg-label-warning">Diproses</span>
                                    @elseif($pengaduan->status === 'selesai')
                                        <span class="badge bg-label-success">Selesai</span>
                                    @elseif($pengaduan->status === 'ditolak')
                                        <span class="badge bg-label-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pengaduan->is_publik)
                                        <span class="badge bg-success"><i class="bx bx-check me-1"></i> Ya</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bx bx-lock-alt me-1"></i> Privat</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.pengaduan.show', $pengaduan->id) }}">
                                                <i class="bx bx-show-alt me-1 text-info"></i> Detail & Tanggapi
                                            </a>
                                            <form action="{{ route('admin.pengaduan.destroy', $pengaduan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengaduan ini dan semua tanggapannya?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-trash me-1 text-danger"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada data pengaduan yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengaduanList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $pengaduanList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

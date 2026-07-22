@extends('admin.layouts.app')

@section('title', 'Destinasi Wisata')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Wisata /</span> Destinasi Wisata
        </h4>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.wisata.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="search">Cari Nama Destinasi</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ $search }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="kategori_id">Kategori Wisata</label>
                        <select name="kategori_id" id="kategori_id" class="form-select">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $kategori == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt me-1"></i> Saring</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Destinasi Wisata</h5>
                <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Destinasi
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Nama & Kategori</th>
                            <th>Tiket Masuk</th>
                            <th>Jarak & Waktu</th>
                            <th>Unggulan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($wisataList as $item)
                            <tr>
                                <td>
                                    @if($item->gambar_utama)
                                        <img src="{{ asset('storage/' . $item->gambar_utama) }}" alt="Cover" class="rounded" style="width: 80px; height: 50px; object-fit: cover;" />
                                    @else
                                        <div class="bg-label-secondary rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 50px;">
                                            <i class="bx bx-landscape fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="d-block text-wrap" style="max-width: 250px;">{{ $item->nama }}</strong>
                                    <span class="badge bg-label-info mt-1">{{ $item->kategoriWisata->nama }}</span>
                                </td>
                                <td>
                                    Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}
                                </td>
                                <td>
                                    Jarak: {{ $item->jarak_dari_desa ?? '-' }} <br/>
                                    <small class="text-muted">{{ $item->hari_buka ?? 'Setiap Hari' }}, {{ $item->jam_operasional ?? '24 Jam' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $item->is_unggulan ? 'bg-warning' : 'bg-label-secondary' }}">
                                        {{ $item->is_unggulan ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->is_published ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.wisata.edit', $item->id) }}" class="btn btn-sm btn-icon" title="Edit & Kelola Detail"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.wisata.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi wisata ini beserta fasilitas dan ulasan di dalamnya?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data destinasi wisata.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($wisataList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $wisataList->appends(['search' => $search, 'kategori_id' => $kategori])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

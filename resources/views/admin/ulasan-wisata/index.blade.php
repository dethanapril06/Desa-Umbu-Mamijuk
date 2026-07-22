@extends('admin.layouts.app')

@section('title', 'Ulasan Wisata')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Wisata /</span> Ulasan & Rating
        </h4>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.ulasan-wisata.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="search">Cari Nama Pengirim atau Isi Ulasan</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ $search }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="rating">Rating Bintang</label>
                        <select name="rating" id="rating" class="form-select">
                            <option value="">-- Semua Rating --</option>
                            <option value="5" {{ $rating == '5' ? 'selected' : '' }}>5 Bintang</option>
                            <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4 Bintang</option>
                            <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3 Bintang</option>
                            <option value="2" {{ $rating == '2' ? 'selected' : '' }}>2 Bintang</option>
                            <option value="1" {{ $rating == '1' ? 'selected' : '' }}>1 Bintang</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt me-1"></i> Saring</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Moderasi Ulasan Wisata</h5>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengirim</th>
                            <th>Destinasi Wisata</th>
                            <th>Rating</th>
                            <th>Ulasan</th>
                            <th>Status</th>
                            <th>Tgl. Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($ulasanList as $ulasan)
                            <tr>
                                <td>
                                    <strong>{{ $ulasan->nama }}</strong>
                                </td>
                                <td>
                                    @if($ulasan->wisata)
                                        <span class="text-wrap d-block text-primary fw-bold" style="max-width: 180px; font-size: 0.85rem;">
                                            {{ $ulasan->wisata->nama }}
                                        </span>
                                    @else
                                        <span class="text-danger small">Wisata terhapus</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-warning">
                                        @for($i=1; $i<=$ulasan->rating; $i++)★@endfor
                                        @for($i=$ulasan->rating+1; $i<=5; $i++)☆@endfor
                                    </span>
                                </td>
                                <td>
                                    <span class="text-wrap d-block text-muted" style="max-width: 350px; white-space: normal;">
                                        {{ $ulasan->ulasan }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $ulasan->is_approved ? 'bg-success' : 'bg-warning' }}">
                                        {{ $ulasan->is_approved ? 'Disetujui' : 'Butuh Moderasi' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $ulasan->created_at ? $ulasan->created_at->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <form action="{{ route('admin.ulasan-wisata.toggle-approve', $ulasan->id) }}" method="POST" onsubmit="return confirm('{{ $ulasan->is_approved ? 'Apakah Anda yakin ingin membatalkan persetujuan ulasan ini?' : 'Apakah Anda yakin ingin menyetujui ulasan ini?' }}');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-icon" title="{{ $ulasan->is_approved ? 'Batalkan Setuju' : 'Setujui' }}"><i class="bx {{ $ulasan->is_approved ? 'bx-x-circle text-warning' : 'bx-check-circle text-success' }}"></i></button>
                                            </form>
                                            <form action="{{ route('admin.ulasan-wisata.destroy', $ulasan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data ulasan tempat wisata.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ulasanList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $ulasanList->appends(['search' => $search, 'rating' => $rating])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

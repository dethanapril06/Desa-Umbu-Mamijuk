@extends('admin.layouts.app')

@section('title', 'Foto Galeri')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Galeri /</span> Foto Galeri
        </h4>

        <!-- Filter Album -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.galeri.index') }}" method="GET" class="row g-3">
                    <div class="col-md-9">
                        <label class="form-label" for="album_galeri_id">Filter Berdasarkan Album</label>
                        <select name="album_galeri_id" id="album_galeri_id" class="form-select">
                            <option value="">-- Semua Album --</option>
                            @foreach($albums as $alb)
                                <option value="{{ $alb->id }}" {{ $albumId == $alb->id ? 'selected' : '' }}>{{ $alb->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt me-1"></i> Saring</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Foto Galeri</h5>
                <a href="{{ route('admin.galeri.create', ['album_galeri_id' => $albumId]) }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Unggah Foto
                </a>
            </div>
            
            <div class="card-body">
                <div class="row g-4">
                    @forelse($photos as $photo)
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 border shadow-none">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $photo->gambar) }}" alt="{{ $photo->caption }}" class="card-img-top" style="height: 180px; object-fit: cover;" />
                                    <span class="badge bg-primary position-absolute top-0 end-0 m-2">Urutan: {{ $photo->urutan }}</span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-label-info mb-2">{{ $photo->albumGaleri->nama }}</span>
                                        <p class="card-text small text-dark mb-0">{{ $photo->caption ?? 'Tanpa Keterangan' }}</p>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-3">
                                        <a href="{{ route('admin.galeri.edit', $photo->id) }}" class="btn btn-xs btn-outline-primary">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.galeri.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <span class="text-muted small">Tidak ada foto dalam saringan galeri ini.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($photos->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $photos->appends(['album_galeri_id' => $albumId])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

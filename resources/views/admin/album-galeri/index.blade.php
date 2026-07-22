@extends('admin.layouts.app')

@section('title', 'Album Galeri')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">Galeri /</span> Album Foto
            </h4>
            <a href="{{ route('admin.album-galeri.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Album
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($albums as $album)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        @if($album->cover)
                            <img src="{{ asset('storage/' . $album->cover) }}" class="card-img-top" alt="{{ $album->nama }}" style="height: 180px; object-fit: cover;" />
                        @else
                            <div class="bg-label-secondary d-flex flex-column align-items-center justify-content-center" style="height: 180px;">
                                <i class="bx bx-folder fs-1 mb-2"></i>
                                <span class="text-muted small">Belum ada cover</span>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-1">{{ $album->nama }}</h5>
                            <p class="card-text text-muted small mb-3">{{ Str::limit($album->deskripsi ?? 'Tidak ada deskripsi.', 80) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                <span class="badge bg-label-info"><i class="bx bx-image me-1"></i> {{ $album->galeri->count() }} Foto</span>
                                
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.galeri.index', ['album_galeri_id' => $album->id]) }}" class="btn btn-sm btn-outline-primary">
                                        Kelola Foto
                                    </a>
                                    <a href="{{ route('admin.album-galeri.edit', $album->id) }}" class="btn btn-sm btn-icon" title="Edit Album">
                                        <i class="bx bx-edit-alt text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.album-galeri.destroy', $album->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus album ini beserta seluruh foto di dalamnya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon" title="Hapus Album">
                                            <i class="bx bx-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="bx bx-folder-open fs-1 mb-2"></i>
                        <p class="mb-0">Belum ada album galeri. Silakan buat album pertama Anda!</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($albums->hasPages())
            <div class="mt-4">
                {{ $albums->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

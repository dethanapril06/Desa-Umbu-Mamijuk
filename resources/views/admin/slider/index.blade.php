@extends('admin.layouts.app')

@section('title', 'Daftar Slider')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pengaturan /</span> Slider
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Slider Banner</h5>
                <a href="{{ route('admin.slider.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Slider
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul & Deskripsi</th>
                            <th>Link Tujuan</th>
                            <th>Urutan Tampil</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($sliders as $slider)
                            <tr>
                                <td>
                                    @if($slider->gambar)
                                        <img src="{{ asset('storage/' . $slider->gambar) }}" alt="Slider" class="rounded" style="width: 120px; height: 60px; object-fit: cover;" />
                                    @else
                                        <span class="text-muted">Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $slider->judul ?? 'Tanpa Judul' }}</strong>
                                    @if($slider->deskripsi)
                                        <br/><small class="text-muted">{{ Str::limit($slider->deskripsi, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" target="_blank" class="text-info"><i class="bx bx-link-external me-1"></i> Buka Link</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $slider->urutan }}
                                </td>
                                <td>
                                    <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $slider->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.slider.edit', $slider->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.slider.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" style="display:inline;">
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
                                <td colspan="6" class="text-center py-4">Tidak ada data slider.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sliders->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $sliders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

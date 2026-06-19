@extends('admin.layouts.app')

@section('title', 'Daftar Sosial Media')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pengaturan /</span> Sosial Media
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Link Sosial Media</h5>
                <a href="{{ route('admin.sosial-media.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Sosial Media
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th>URL Link</th>
                            <th>Urutan Tampil</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($socials as $social)
                            <tr>
                                <td>
                                    <strong>{{ $social->platform }}</strong>
                                </td>
                                <td>
                                    <a href="{{ $social->url }}" target="_blank" class="text-info"><i class="bx bx-link-external me-1"></i> {{ $social->url }}</a>
                                </td>
                                <td>
                                    {{ $social->urutan }}
                                </td>
                                <td>
                                    <span class="badge {{ $social->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $social->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.sosial-media.edit', $social->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.sosial-media.destroy', $social->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" style="display:inline;">
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
                                <td colspan="5" class="text-center py-4">Tidak ada data sosial media.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($socials->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $socials->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

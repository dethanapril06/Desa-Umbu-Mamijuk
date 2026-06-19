@extends('admin.layouts.app')

@section('title', 'Tag Berita')

@section('content')
<div class="row">
    <div class="col-12 col-md-10 offset-md-1">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Berita /</span> Tag Berita
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Tag Berita</h5>
                <a href="{{ route('admin.tag.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Tag
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Tag</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($tags as $tag)
                            <tr>
                                <td><strong>{{ $tag->nama }}</strong></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.tag.edit', $tag->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.tag.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tag ini? Tag akan dilepas dari semua berita terkait.');" style="display:inline;">
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
                                <td colspan="3" class="text-center py-4">Tidak ada data tag berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tags->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $tags->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

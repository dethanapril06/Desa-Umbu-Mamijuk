@extends('admin.layouts.app')

@section('title', 'Kategori Pengaduan')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pelayanan Publik /</span> Kategori Pengaduan
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kategori Pengaduan</h5>
                <a href="{{ route('admin.kategori-pengaduan.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kategori
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($kategoriList as $kategori)
                            <tr>
                                <td><strong>{{ $kategori->nama }}</strong></td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.kategori-pengaduan.edit', $kategori->id) }}" class="btn btn-sm btn-icon" title="Edit"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.kategori-pengaduan.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4">Tidak ada data kategori pengaduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategoriList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $kategoriList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

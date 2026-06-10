@extends('admin.layouts.app')

@section('title', 'Daftar RT / RW')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Wilayah RT / RW
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar RT / RW</h5>
                <a href="{{ route('admin.rt-rw.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah RT / RW
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Dusun</th>
                            <th>No. RT</th>
                            <th>No. RW</th>
                            <th>Ketua RT</th>
                            <th>Ketua RW</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($rtRwList as $rtRw)
                            <tr>
                                <td><strong>{{ $rtRw->dusun->nama }}</strong></td>
                                <td>RT {{ $rtRw->no_rt }}</td>
                                <td>RW {{ $rtRw->no_rw }}</td>
                                <td>{{ $rtRw->ketua_rt ?? '-' }}</td>
                                <td>{{ $rtRw->ketua_rw ?? '-' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.rt-rw.edit', $rtRw->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.rt-rw.destroy', $rtRw->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" style="display:inline;">
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
                                <td colspan="6" class="text-center py-4">Tidak ada data RT / RW.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rtRwList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $rtRwList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

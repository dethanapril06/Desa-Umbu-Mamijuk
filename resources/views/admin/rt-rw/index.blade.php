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
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.rt-rw.edit', $rtRw->id) }}" class="btn btn-sm btn-icon" title="Edit"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.rt-rw.destroy', $rtRw->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
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

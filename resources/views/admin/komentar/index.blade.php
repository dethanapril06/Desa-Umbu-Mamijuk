@extends('admin.layouts.app')

@section('title', 'Komentar Berita')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Berita /</span> Komentar Berita
        </h4>

        <!-- Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.komentar-berita.index') }}" method="GET" class="row g-3">
                    <div class="col-md-9">
                        <label class="form-label" for="search">Cari Nama, Komentar, atau Berita</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ $search }}" />
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Moderasi Komentar Masuk</h5>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengirim</th>
                            <th>Berita</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th>Tgl. Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($komentarList as $komentar)
                            <tr>
                                <td>
                                    <strong>{{ $komentar->nama }}</strong>
                                    @if($komentar->email)
                                        <br/><small class="text-muted">{{ $komentar->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($komentar->berita)
                                        <span class="text-wrap d-block" style="max-width: 200px; font-size: 0.85rem;">
                                            {{ $komentar->berita->judul }}
                                        </span>
                                    @else
                                        <span class="text-danger small">Berita terhapus</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-wrap d-block text-muted" style="max-width: 350px; white-space: normal;">
                                        {{ $komentar->komentar }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $komentar->is_approved ? 'bg-success' : 'bg-warning' }}">
                                        {{ $komentar->is_approved ? 'Disetujui' : 'Butuh Moderasi' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $komentar->created_at ? $komentar->created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <form action="{{ route('admin.komentar-berita.toggle-approve', $komentar->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ $komentar->is_approved ? 'Apakah Anda yakin ingin membatalkan persetujuan komentar ini?' : 'Apakah Anda yakin ingin menyetujui komentar ini?' }}');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-icon" title="{{ $komentar->is_approved ? 'Batalkan Setuju' : 'Setujui' }}"><i class="bx {{ $komentar->is_approved ? 'bx-x-circle text-warning' : 'bx-check-circle text-success' }}"></i></button>
                                            </form>

                                            <form action="{{ route('admin.komentar-berita.destroy', $komentar->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada data komentar berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($komentarList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $komentarList->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

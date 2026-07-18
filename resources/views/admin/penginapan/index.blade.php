@extends('admin.layouts.app')

@section('title', 'Daftar Penginapan / Homestay')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Konten Website /</span> Penginapan
        </h4>

        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0">Daftar Penginapan / Homestay Desa</h5>
                
                <a href="{{ route('admin.penginapan.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Penginapan
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Info Penginapan</th>
                            <th>Kisaran Harga</th>
                            <th>Jarak / Lokasi</th>
                            <th>Wisata Terdekat</th>
                            <th>WhatsApp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($penginapans as $penginapan)
                            <tr>
                                <td style="width: 90px;">
                                    @if($penginapan->foto)
                                        <img src="{{ asset('storage/' . $penginapan->foto) }}" alt="Foto Penginapan" class="rounded" style="width: 80px; height: 50px; object-fit: cover;" />
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 50px; font-size: 1.2rem;">
                                            <i class="bx bx-home-heart"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $penginapan->nama_penginapan }}</strong>
                                    <div class="text-muted small">{{ $penginapan->jenis ?? 'Homestay' }}</div>
                                    @if($penginapan->fasilitas_singkat)
                                        <div class="text-muted small mt-1">{{ $penginapan->fasilitas_singkat }}</div>
                                    @endif
                                </td>
                                <td>{{ $penginapan->kisaran_harga ?? '-' }}</td>
                                <td>{{ $penginapan->jarak ?? '-' }}</td>
                                <td>
                                    @if($penginapan->wisata->count() > 0)
                                        {{ $penginapan->wisata->pluck('nama')->implode(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($penginapan->no_telepon)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $penginapan->no_telepon)) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                            {{ $penginapan->no_telepon }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.penginapan.edit', $penginapan->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.penginapan.destroy', $penginapan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penginapan ini?');" style="display:inline;">
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
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data penginapan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Daftar Penduduk')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Data Penduduk
        </h4>

        <!-- Filter & Search -->
        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.penduduk.index') }}" method="GET" id="filter-form" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="search_field">Pilih Field Pencarian</label>
                        <select name="search_field" id="search_field" class="form-select">
                            <option value="nama_lengkap" {{ ($searchField ?? 'nama_lengkap') == 'nama_lengkap' ? 'selected' : '' }}>Nama Lengkap</option>
                            <option value="nik" {{ ($searchField ?? '') == 'nik' ? 'selected' : '' }}>NIK</option>
                            <option value="tempat_lahir" {{ ($searchField ?? '') == 'tempat_lahir' ? 'selected' : '' }}>Tempat Lahir</option>
                            <option value="pekerjaan" {{ ($searchField ?? '') == 'pekerjaan' ? 'selected' : '' }}>Pekerjaan</option>
                            <option value="agama" {{ ($searchField ?? '') == 'agama' ? 'selected' : '' }}>Agama</option>
                            <option value="status_hubungan_keluarga" {{ ($searchField ?? '') == 'status_hubungan_keluarga' ? 'selected' : '' }}>Status Hubungan KK</option>
                            <option value="nama_ayah" {{ ($searchField ?? '') == 'nama_ayah' ? 'selected' : '' }}>Nama Ayah</option>
                            <option value="nama_ibu" {{ ($searchField ?? '') == 'nama_ibu' ? 'selected' : '' }}>Nama Ibu</option>
                            <option value="no_telepon" {{ ($searchField ?? '') == 'no_telepon' ? 'selected' : '' }}>No. Telepon</option>
                            <option value="jenis_kelamin" {{ ($searchField ?? '') == 'jenis_kelamin' ? 'selected' : '' }}>Jenis Kelamin</option>
                            <option value="status" {{ ($searchField ?? '') == 'status' ? 'selected' : '' }}>Status Penduduk</option>
                        </select>
                    </div>

                    <div class="col-md-5" id="search_container">
                        <label class="form-label" for="search_input">Kata Kunci / Pilihan</label>
                        <!-- Container akan dinamis diisi text/select oleh JavaScript di bawah -->
                        <input type="text" name="search" id="search_input" class="form-control" placeholder="Ketik kata kunci pencarian..." value="{{ $search ?? '' }}" />
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bx bx-search me-1"></i> Cari</button>
                        @if(!empty($search))
                            <a href="{{ route('admin.penduduk.index') }}" class="btn btn-secondary" title="Reset Filter"><i class="bx bx-refresh"></i> Reset</a>
                        @endif
                    </div>
                </form>

                @if(!empty($search))
                    @php
                        $fieldLabels = [
                            'nama_lengkap' => 'Nama Lengkap',
                            'nik' => 'NIK',
                            'tempat_lahir' => 'Tempat Lahir',
                            'pekerjaan' => 'Pekerjaan',
                            'agama' => 'Agama',
                            'status_hubungan_keluarga' => 'Status Hubungan KK',
                            'nama_ayah' => 'Nama Ayah',
                            'nama_ibu' => 'Nama Ibu',
                            'no_telepon' => 'No. Telepon',
                            'jenis_kelamin' => 'Jenis Kelamin',
                            'status' => 'Status Penduduk',
                        ];
                        $displayLabel = $fieldLabels[$searchField ?? 'nama_lengkap'] ?? ($searchField ?? 'Nama Lengkap');
                        $displayValue = $search;
                        if (($searchField ?? '') === 'jenis_kelamin') {
                            $displayValue = $search === 'laki-laki' ? 'Laki-laki' : ($search === 'perempuan' ? 'Perempuan' : $search);
                        } elseif (($searchField ?? '') === 'status') {
                            $displayValue = ucfirst($search);
                        }
                    @endphp
                    <div class="alert alert-info d-flex align-items-center justify-content-between mb-0 mt-3 py-2 px-3" role="alert">
                        <div>
                            <i class="bx bx-info-circle me-1"></i> Filter aktif pada <strong>{{ $displayLabel }}</strong> : <span class="badge bg-primary">{{ $displayValue }}</span>
                        </div>
                        <a href="{{ route('admin.penduduk.index') }}" class="text-decoration-none text-info fw-bold small">Hapus Filter ×</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Biodata Penduduk</h5>
                <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Penduduk
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>No. KK</th>
                            <th>L/P</th>
                            <th>RT / RW / Dusun</th>
                            <th>Pekerjaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($pendudukList as $penduduk)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.penduduk.show', $penduduk->id) }}" class="fw-bold text-primary">
                                        {{ $penduduk->nik }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $penduduk->nama_lengkap }}</strong>
                                </td>
                                <td>
                                    @if($penduduk->keluarga)
                                        <a href="{{ route('admin.keluarga.show', $penduduk->keluarga_id) }}" class="text-secondary">
                                            {{ $penduduk->keluarga->no_kk }}
                                        </a>
                                    @else
                                        <span class="text-danger">Belum ada KK</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $penduduk->jenis_kelamin === 'laki-laki' ? 'L' : 'P' }}
                                </td>
                                <td>
                                    @if($penduduk->keluarga && $penduduk->keluarga->rtRw)
                                        RT {{ $penduduk->keluarga->rtRw->no_rt }} / RW {{ $penduduk->keluarga->rtRw->no_rw }} <br/>
                                        <small class="text-muted">{{ $penduduk->keluarga->rtRw->dusun->nama }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $penduduk->pekerjaan ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge {{ $penduduk->status === 'aktif' ? 'bg-success' : ($penduduk->status === 'meninggal' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($penduduk->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.penduduk.show', $penduduk->id) }}">
                                                <i class="bx bx-show me-1 text-info"></i> Profil Detail
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.penduduk.edit', $penduduk->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit Biodata
                                            </a>
                                            <form action="{{ route('admin.penduduk.destroy', $penduduk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penduduk ini?');" style="display:inline;">
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
                                <td colspan="8" class="text-center py-4">Tidak ada data penduduk yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendudukList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $pendudukList->appends(['search_field' => $searchField ?? 'nama_lengkap', 'search' => $search ?? ''])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchField = document.getElementById('search_field');
    const searchContainer = document.getElementById('search_container');
    const currentValue = @json($search ?? '');

    function renderSearchInput(field, initialVal = '') {
        let html = '<label class="form-label" for="search_input">Kata Kunci / Pilihan</label>';
        if (field === 'jenis_kelamin') {
            html += `
                <select name="search" id="search_input" class="form-select">
                    <option value="laki-laki" ${initialVal === 'laki-laki' ? 'selected' : ''}>Laki-laki</option>
                    <option value="perempuan" ${initialVal === 'perempuan' ? 'selected' : ''}>Perempuan</option>
                </select>
            `;
        } else if (field === 'status') {
            html += `
                <select name="search" id="search_input" class="form-select">
                    <option value="aktif" ${initialVal === 'aktif' || initialVal === '' ? 'selected' : ''}>Aktif</option>
                    <option value="pindah" ${initialVal === 'pindah' ? 'selected' : ''}>Pindah</option>
                    <option value="meninggal" ${initialVal === 'meninggal' ? 'selected' : ''}>Meninggal</option>
                </select>
            `;
        } else {
            let placeholder = 'Ketik kata kunci pencarian...';
            if (field === 'nik') placeholder = 'Ketik 16 digit NIK atau sebagian...';
            if (field === 'no_telepon') placeholder = 'Ketik nomor telepon...';
            html += `<input type="text" name="search" id="search_input" class="form-control" placeholder="${placeholder}" value="${initialVal}" />`;
        }
        searchContainer.innerHTML = html;
    }

    // Render awal saat load jika current field adalah select
    if (searchField.value === 'jenis_kelamin' || searchField.value === 'status') {
        renderSearchInput(searchField.value, currentValue);
    }

    // Ganti saat dropdown field diubah
    searchField.addEventListener('change', function() {
        renderSearchInput(this.value, '');
    });
});
</script>
@endpush

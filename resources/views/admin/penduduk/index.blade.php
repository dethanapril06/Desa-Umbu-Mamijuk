@extends('admin.layouts.app')

@section('title', 'Daftar Penduduk')

@section('content')
@php
    $fieldLabels = [
        'nama_lengkap' => 'Nama Lengkap',
        'nik' => 'NIK',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'pekerjaan' => 'Pekerjaan',
        'agama' => 'Agama',
        'pendidikan_terakhir' => 'Pendidikan Terakhir',
        'status_perkawinan' => 'Status Perkawinan',
        'status_hubungan_keluarga' => 'Status Hubungan KK',
        'kewarganegaraan' => 'Kewarganegaraan',
        'golongan_darah' => 'Golongan Darah',
        'nama_ayah' => 'Nama Ayah',
        'nama_ibu' => 'Nama Ibu',
        'no_telepon' => 'No. Telepon',
        'no_paspor' => 'No. Paspor',
        'no_kitas_kitap' => 'No. KITAS / KITAP',
        'jenis_kelamin' => 'Jenis Kelamin',
        'status' => 'Status Penduduk',
    ];
    $showDynamicColumn = !in_array($searchField ?? 'nama_lengkap', ['nik', 'nama_lengkap', 'pekerjaan', 'status', 'jenis_kelamin']);
@endphp
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Data Penduduk
        </h4>

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
                            <option value="tanggal_lahir" {{ ($searchField ?? '') == 'tanggal_lahir' ? 'selected' : '' }}>Tanggal Lahir</option>
                            <option value="pekerjaan" {{ ($searchField ?? '') == 'pekerjaan' ? 'selected' : '' }}>Pekerjaan</option>
                            <option value="agama" {{ ($searchField ?? '') == 'agama' ? 'selected' : '' }}>Agama</option>
                            <option value="pendidikan_terakhir" {{ ($searchField ?? '') == 'pendidikan_terakhir' ? 'selected' : '' }}>Pendidikan Terakhir</option>
                            <option value="status_perkawinan" {{ ($searchField ?? '') == 'status_perkawinan' ? 'selected' : '' }}>Status Perkawinan</option>
                            <option value="status_hubungan_keluarga" {{ ($searchField ?? '') == 'status_hubungan_keluarga' ? 'selected' : '' }}>Status Hubungan KK</option>
                            <option value="kewarganegaraan" {{ ($searchField ?? '') == 'kewarganegaraan' ? 'selected' : '' }}>Kewarganegaraan</option>
                            <option value="golongan_darah" {{ ($searchField ?? '') == 'golongan_darah' ? 'selected' : '' }}>Golongan Darah</option>
                            <option value="nama_ayah" {{ ($searchField ?? '') == 'nama_ayah' ? 'selected' : '' }}>Nama Ayah</option>
                            <option value="nama_ibu" {{ ($searchField ?? '') == 'nama_ibu' ? 'selected' : '' }}>Nama Ibu</option>
                            <option value="no_telepon" {{ ($searchField ?? '') == 'no_telepon' ? 'selected' : '' }}>No. Telepon</option>
                            <option value="no_paspor" {{ ($searchField ?? '') == 'no_paspor' ? 'selected' : '' }}>No. Paspor</option>
                            <option value="no_kitas_kitap" {{ ($searchField ?? '') == 'no_kitas_kitap' ? 'selected' : '' }}>No. KITAS / KITAP</option>
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
                        $displayLabel = $fieldLabels[$searchField ?? 'nama_lengkap'] ?? ($searchField ?? 'Nama Lengkap');
                        $displayValue = $search;
                        if (($searchField ?? '') === 'jenis_kelamin') {
                            $displayValue = $search === 'laki-laki' ? 'Laki-laki' : ($search === 'perempuan' ? 'Perempuan' : ucfirst($search));
                        } elseif (in_array(($searchField ?? ''), ['status', 'agama', 'kewarganegaraan'])) {
                            $displayValue = ucfirst($search);
                        } elseif (in_array(($searchField ?? ''), ['pendidikan_terakhir', 'status_perkawinan', 'status_hubungan_keluarga'])) {
                            $displayValue = ucwords(str_replace('_', ' ', $search));
                        } elseif (($searchField ?? '') === 'tanggal_lahir') {
                            $displayValue = \Carbon\Carbon::parse($search)->format('d/m/Y');
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
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="mb-0">Daftar Biodata Penduduk</h5>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#reportPendudukModal">
                        <i class="bx bx-spreadsheet me-1"></i> Report Excel
                    </button>
                    <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Tambah Penduduk
                    </a>
                </div>
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
                            @if($showDynamicColumn)
                                <th class="text-primary">{{ $fieldLabels[$searchField] ?? ucwords(str_replace('_', ' ', $searchField)) }}</th>
                            @endif
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
                                @if($showDynamicColumn)
                                    <td class="text-primary fw-semibold">
                                        @php
                                            $val = $penduduk->{$searchField} ?? null;
                                        @endphp
                                        @if($searchField === 'tanggal_lahir')
                                            {{ $val ? \Carbon\Carbon::parse($val)->format('d/m/Y') : '-' }}
                                        @elseif(in_array($searchField, ['pendidikan_terakhir', 'status_perkawinan', 'status_hubungan_keluarga']))
                                            {{ ucwords(str_replace('_', ' ', $val ?? '-')) }}
                                        @elseif(in_array($searchField, ['status', 'agama', 'kewarganegaraan']))
                                            {{ ucfirst($val ?? '-') }}
                                        @else
                                            {{ $val ?: '-' }}
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <span class="badge {{ $penduduk->status === 'aktif' ? 'bg-success' : ($penduduk->status === 'meninggal' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($penduduk->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.penduduk.show', $penduduk->id) }}" class="btn btn-sm btn-icon" title="Profil Detail"><i class="bx bx-show  text-info"></i></a>
                                            <a href="{{ route('admin.penduduk.edit', $penduduk->id) }}" class="btn btn-sm btn-icon" title="Edit Biodata"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.penduduk.destroy', $penduduk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penduduk ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $showDynamicColumn ? 9 : 8 }}" class="text-center py-4">Tidak ada data penduduk yang cocok.</td>
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

<div class="modal fade" id="reportPendudukModal" tabindex="-1" aria-labelledby="reportPendudukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.penduduk.report') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportPendudukModalLabel">Report Excel Penduduk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_status">Status Penduduk</label>
                            <select name="status" id="report_penduduk_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="pindah">Pindah</option>
                                <option value="meninggal">Meninggal</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="report_penduduk_jenis_kelamin" class="form-select">
                                <option value="">Semua</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_dusun">Dusun</label>
                            <select name="dusun_id" id="report_penduduk_dusun" class="form-select">
                                <option value="">Semua Dusun</option>
                                @foreach($dusunList as $dusun)
                                    <option value="{{ $dusun->id }}">{{ $dusun->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_rt_rw">RT / RW</label>
                            <select name="rt_rw_id" id="report_penduduk_rt_rw" class="form-select">
                                <option value="">Semua RT / RW</option>
                                @foreach($rtRwList as $rtRw)
                                    <option value="{{ $rtRw->id }}">RT {{ $rtRw->no_rt }} / RW {{ $rtRw->no_rw }} - {{ $rtRw->dusun->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_pendidikan">Pendidikan</label>
                            <select name="pendidikan_terakhir" id="report_penduduk_pendidikan" class="form-select">
                                <option value="">Semua Pendidikan</option>
                                <option value="tidak_sekolah">Tidak / Belum Sekolah</option>
                                <option value="sd">SD / Sederajat</option>
                                <option value="smp">SMP / Sederajat</option>
                                <option value="sma">SMA / Sederajat</option>
                                <option value="d1">D1</option>
                                <option value="d2">D2</option>
                                <option value="d3">D3</option>
                                <option value="s1">S1 / D4</option>
                                <option value="s2">S2</option>
                                <option value="s3">S3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_perkawinan">Status Perkawinan</label>
                            <select name="status_perkawinan" id="report_penduduk_perkawinan" class="form-select">
                                <option value="">Semua</option>
                                <option value="belum_kawin">Belum Kawin</option>
                                <option value="kawin">Kawin</option>
                                <option value="cerai_hidup">Cerai Hidup</option>
                                <option value="cerai_mati">Cerai Mati</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_hubungan">Hubungan Keluarga</label>
                            <select name="status_hubungan_keluarga" id="report_penduduk_hubungan" class="form-select">
                                <option value="">Semua Hubungan</option>
                                <option value="kepala_keluarga">Kepala Keluarga</option>
                                <option value="istri">Istri</option>
                                <option value="anak">Anak</option>
                                <option value="menantu">Menantu</option>
                                <option value="cucu">Cucu</option>
                                <option value="orang_tua">Orang Tua</option>
                                <option value="mertua">Mertua</option>
                                <option value="famili_lain">Famili Lain</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_lahir_mulai">Tanggal Lahir Mulai</label>
                            <input type="date" name="tanggal_lahir_mulai" id="report_penduduk_lahir_mulai" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="report_penduduk_lahir_selesai">Tanggal Lahir Selesai</label>
                            <input type="date" name="tanggal_lahir_selesai" id="report_penduduk_lahir_selesai" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-download me-1"></i> Download Excel
                    </button>
                </div>
            </form>
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
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="laki-laki" ${initialVal === 'laki-laki' ? 'selected' : ''}>Laki-laki</option>
                    <option value="perempuan" ${initialVal === 'perempuan' ? 'selected' : ''}>Perempuan</option>
                </select>
            `;
        } else if (field === 'status') {
            html += `
                <select name="search" id="search_input" class="form-select">
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif" ${initialVal === 'aktif' || initialVal === '' ? 'selected' : ''}>Aktif</option>
                    <option value="pindah" ${initialVal === 'pindah' ? 'selected' : ''}>Pindah</option>
                    <option value="meninggal" ${initialVal === 'meninggal' ? 'selected' : ''}>Meninggal</option>
                </select>
            `;
        } else if (field === 'agama') {
            const agamas = ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'lainnya'];
            html += `<select name="search" id="search_input" class="form-select"><option value="">-- Pilih Agama --</option>`;
            agamas.forEach(a => {
                html += `<option value="${a}" ${initialVal === a ? 'selected' : ''}>${a.charAt(0).toUpperCase() + a.slice(1)}</option>`;
            });
            html += `</select>`;
        } else if (field === 'pendidikan_terakhir') {
            const pendidikans = [
                {val: 'tidak_sekolah', label: 'Tidak / Belum Sekolah'},
                {val: 'sd', label: 'SD / Sederajat'},
                {val: 'smp', label: 'SMP / Sederajat'},
                {val: 'sma', label: 'SMA / Sederajat'},
                {val: 'd1', label: 'Diploma 1 (D1)'},
                {val: 'd2', label: 'Diploma 2 (D2)'},
                {val: 'd3', label: 'Diploma 3 (D3)'},
                {val: 's1', label: 'Strata 1 (S1 / D4)'},
                {val: 's2', label: 'Strata 2 (S2)'},
                {val: 's3', label: 'Strata 3 (S3)'}
            ];
            html += `<select name="search" id="search_input" class="form-select"><option value="">-- Pilih Pendidikan --</option>`;
            pendidikans.forEach(p => {
                html += `<option value="${p.val}" ${initialVal === p.val ? 'selected' : ''}>${p.label}</option>`;
            });
            html += `</select>`;
        } else if (field === 'status_perkawinan') {
            const kawins = [
                {val: 'belum_kawin', label: 'Belum Kawin'},
                {val: 'kawin', label: 'Kawin'},
                {val: 'cerai_hidup', label: 'Cerai Hidup'},
                {val: 'cerai_mati', label: 'Cerai Mati'}
            ];
            html += `<select name="search" id="search_input" class="form-select"><option value="">-- Pilih Status Perkawinan --</option>`;
            kawins.forEach(k => {
                html += `<option value="${k.val}" ${initialVal === k.val ? 'selected' : ''}>${k.label}</option>`;
            });
            html += `</select>`;
        } else if (field === 'status_hubungan_keluarga') {
            const hubungans = [
                {val: 'kepala_keluarga', label: 'Kepala Keluarga'},
                {val: 'istri', label: 'Istri'},
                {val: 'anak', label: 'Anak'},
                {val: 'menantu', label: 'Menantu'},
                {val: 'cucu', label: 'Cucu'},
                {val: 'orang_tua', label: 'Orang Tua'},
                {val: 'mertua', label: 'Mertua'},
                {val: 'famili_lain', label: 'Famili Lain'},
                {val: 'lainnya', label: 'Lainnya'}
            ];
            html += `<select name="search" id="search_input" class="form-select"><option value="">-- Pilih Hubungan KK --</option>`;
            hubungans.forEach(h => {
                html += `<option value="${h.val}" ${initialVal === h.val ? 'selected' : ''}>${h.label}</option>`;
            });
            html += `</select>`;
        } else if (field === 'kewarganegaraan') {
            html += `
                <select name="search" id="search_input" class="form-select">
                    <option value="">-- Pilih Kewarganegaraan --</option>
                    <option value="WNI" ${initialVal === 'WNI' ? 'selected' : ''}>WNI</option>
                    <option value="WNA" ${initialVal === 'WNA' ? 'selected' : ''}>WNA</option>
                </select>
            `;
        } else if (field === 'golongan_darah') {
            const golongans = ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            html += `<select name="search" id="search_input" class="form-select"><option value="">-- Pilih Golongan Darah --</option>`;
            golongans.forEach(g => {
                html += `<option value="${g}" ${initialVal === g ? 'selected' : ''}>${g}</option>`;
            });
            html += `</select>`;
        } else if (field === 'tanggal_lahir') {
            html += `<input type="date" name="search" id="search_input" class="form-control" value="${initialVal}" />`;
        } else {
            let placeholder = 'Ketik kata kunci pencarian...';
            if (field === 'nik') placeholder = 'Ketik 16 digit NIK atau sebagian...';
            if (field === 'no_telepon') placeholder = 'Ketik nomor telepon...';
            html += `<input type="text" name="search" id="search_input" class="form-control" placeholder="${placeholder}" value="${initialVal}" />`;
        }
        searchContainer.innerHTML = html;
    }

    // Render awal saat load untuk field select/date
    const selectOrDateFields = ['jenis_kelamin', 'status', 'agama', 'pendidikan_terakhir', 'status_perkawinan', 'status_hubungan_keluarga', 'kewarganegaraan', 'golongan_darah', 'tanggal_lahir'];
    if (selectOrDateFields.includes(searchField.value)) {
        renderSearchInput(searchField.value, currentValue);
    }

    // Ganti saat dropdown field diubah
    searchField.addEventListener('change', function() {
        renderSearchInput(this.value, '');
    });
});
</script>
@endpush

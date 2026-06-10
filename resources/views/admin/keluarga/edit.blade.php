@extends('admin.layouts.app')

@section('title', 'Edit Kartu Keluarga')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Keluarga /</span> Edit Kartu Keluarga
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Kartu Keluarga (KK)</h5>
                <a href="{{ route('admin.keluarga.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.keluarga.update', $keluarga->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="no_kk">Nomor Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_kk" name="no_kk" value="{{ old('no_kk', $keluarga->no_kk) }}" maxlength="16" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="rt_rw_id">Pilih RT / RW / Dusun <span class="text-danger">*</span></label>
                        <select class="form-select" id="rt_rw_id" name="rt_rw_id" required>
                            @foreach($rtRwList as $r)
                                <option value="{{ $r->id }}" {{ old('rt_rw_id', $keluarga->rt_rw_id) == $r->id ? 'selected' : '' }}>
                                    RT {{ $r->no_rt }} / RW {{ $r->no_rw }} - {{ $r->dusun->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="alamat">Alamat KK Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="4" required>{{ old('alamat', $keluarga->alamat) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="kode_pos">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $keluarga->kode_pos) }}" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_terdaftar">Tanggal Terdaftar KK</label>
                            <input type="date" class="form-control" id="tanggal_terdaftar" name="tanggal_terdaftar" value="{{ old('tanggal_terdaftar', $keluarga->tanggal_terdaftar ? $keluarga->tanggal_terdaftar->format('Y-m-d') : '') }}" />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

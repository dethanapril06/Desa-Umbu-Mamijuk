@extends('admin.layouts.app')

@section('title', 'Tambah RT / RW')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">RT / RW /</span> Tambah RT / RW
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah RT / RW</h5>
                <a href="{{ route('admin.rt-rw.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.rt-rw.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="dusun_id">Pilih Dusun <span class="text-danger">*</span></label>
                        <select class="form-select" id="dusun_id" name="dusun_id" required>
                            <option value="">-- Pilih Dusun --</option>
                            @foreach($dusunList as $d)
                                <option value="{{ $d->id }}" {{ old('dusun_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_rt">No. RT <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_rt" name="no_rt" value="{{ old('no_rt') }}" placeholder="Contoh: 001 atau 01" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_rw">No. RW <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_rw" name="no_rw" value="{{ old('no_rw') }}" placeholder="Contoh: 002 atau 02" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ketua_rt">Nama Ketua RT (Opsional)</label>
                        <input type="text" class="form-control" id="ketua_rt" name="ketua_rt" value="{{ old('ketua_rt') }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ketua_rw">Nama Ketua RW (Opsional)</label>
                        <input type="text" class="form-control" id="ketua_rw" name="ketua_rw" value="{{ old('ketua_rw') }}" />
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan RT / RW
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

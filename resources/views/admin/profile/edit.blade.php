@extends('admin.layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pengaturan /</span> Profil Saya
        </h4>

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

        {{-- Card 1: Account Information --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bx bx-user me-1 text-primary"></i> Informasi Akun</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan Profil
                    </button>
                </form>
            </div>
        </div>

        {{-- Card 2: Security (Password Update) --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bx bx-lock-alt me-1 text-warning"></i> Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="current_password">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="••••••••" required />
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required />
                            <div class="form-text">Minimal 8 karakter.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bx bx-key me-1"></i> Perbarui Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

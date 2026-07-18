@extends('admin.layouts.app')

@section('title', 'Tambah Sosial Media')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Sosial Media /</span> Tambah Sosial Media
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Sosial Media</h5>
                <a href="{{ route('admin.sosial-media.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.sosial-media.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="platform_select">Nama Platform <span class="text-danger">*</span></label>
                        <select class="form-select mb-2" id="platform_select" required>
                            <option value="" disabled selected>Pilih Platform</option>
                            <option value="Facebook" {{ old('platform') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="Instagram" {{ old('platform') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="YouTube" {{ old('platform') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                            <option value="TikTok" {{ old('platform') == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                            <option value="Twitter / X" {{ old('platform') == 'Twitter / X' ? 'selected' : '' }}>Twitter / X</option>
                            <option value="WhatsApp" {{ old('platform') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="Telegram" {{ old('platform') == 'Telegram' ? 'selected' : '' }}>Telegram</option>
                            <option value="Lainnya" {{ (old('platform') && !in_array(old('platform'), ['Facebook', 'Instagram', 'YouTube', 'TikTok', 'Twitter / X', 'WhatsApp', 'Telegram'])) ? 'selected' : '' }}>Lainnya (Website / Lain-lain)</option>
                        </select>
                        <input type="text" class="form-control {{ (old('platform') && !in_array(old('platform'), ['Facebook', 'Instagram', 'YouTube', 'TikTok', 'Twitter / X', 'WhatsApp', 'Telegram'])) ? '' : 'd-none' }}" id="platform" name="platform" value="{{ old('platform') }}" placeholder="Masukkan nama platform..." required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="url">Link / URL Profil <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="url" name="url" value="{{ old('url') }}" placeholder="Contoh: https://facebook.com/username" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="icon">Icon <span class="text-danger">*</span></label>
                        @include('admin.layouts.partials.icon-picker', [
                            'id' => 'icon',
                            'name' => 'icon',
                            'value' => old('icon'),
                            'type' => 'sosial_media'
                        ])
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di website)</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Sosial Media
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const platformSelect = document.getElementById('platform_select');
        const platformInput = document.getElementById('platform');
        const iconInput = document.getElementById('icon');
        
        const platformIcons = {
            'Facebook': 'bxl-facebook-circle',
            'Instagram': 'bxl-instagram',
            'YouTube': 'bxl-youtube',
            'TikTok': 'bxl-tiktok',
            'Twitter / X': 'bxl-twitter',
            'WhatsApp': 'bxl-whatsapp',
            'Telegram': 'bxl-telegram',
            'Lainnya': 'bx-globe'
        };

        function updateIconPicker(targetIcon) {
            if (!targetIcon) return;
            iconInput.value = targetIcon;
            
            const pickerContainer = document.getElementById('picker-container-icon');
            if (pickerContainer) {
                const iconPreview = pickerContainer.querySelector('.selected-icon-preview');
                const iconLabel = pickerContainer.querySelector('.selected-icon-label');
                const grid = pickerContainer.querySelector('.icon-grid');
                
                iconPreview.innerHTML = `<i class="bx ${targetIcon} fs-4 text-primary"></i>`;
                
                const found = window.boxiconLists.sosial_media.find(i => i.class === targetIcon);
                iconLabel.textContent = found ? found.label + ' (' + targetIcon + ')' : targetIcon;
                
                grid.querySelectorAll('.icon-option-btn').forEach(btn => {
                    if (btn.dataset.icon === targetIcon) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
        }

        if (platformSelect && platformInput && iconInput) {
            platformSelect.addEventListener('change', function() {
                const selectedVal = this.value;
                if (selectedVal === 'Lainnya') {
                    platformInput.classList.remove('d-none');
                    if (['Facebook', 'Instagram', 'YouTube', 'TikTok', 'Twitter / X', 'WhatsApp', 'Telegram'].includes(platformInput.value)) {
                        platformInput.value = '';
                    }
                    platformInput.focus();
                    updateIconPicker('bx-globe');
                } else {
                    platformInput.classList.add('d-none');
                    platformInput.value = selectedVal;
                    
                    const targetIcon = platformIcons[selectedVal];
                    updateIconPicker(targetIcon);
                }
            });
        }

        if (platformInput) {
            platformInput.addEventListener('blur', function() {
                this.value = this.value.replace(/\s+/g, ' ').trim().replace(/\w\S*/g, function(txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                });
            });
        }

        const urlInput = document.getElementById('url');
        if (urlInput) {
            urlInput.addEventListener('blur', function() {
                this.value = this.value.replace(/\s+/g, ' ').trim();
            });
        }
    });
</script>
@endpush
@endsection

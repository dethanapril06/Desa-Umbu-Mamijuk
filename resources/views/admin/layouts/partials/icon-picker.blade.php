@php
    $id = $id ?? 'icon-picker-' . uniqid();
    $name = $name ?? 'icon';
    $value = $value ?? '';
    $type = $type ?? 'berita';
@endphp

<div class="icon-picker-container" id="picker-container-{{ $id }}">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}">
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center justify-content-between w-100" 
                type="button" 
                id="btn-{{ $id }}" 
                data-bs-toggle="dropdown" 
                data-bs-auto-close="outside"
                aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <span class="selected-icon-preview">
                    @if($value)
                        <i class="bx {{ $value }} fs-4 text-primary"></i>
                    @else
                        <i class="bx bx-help-circle fs-4 text-muted"></i>
                    @endif
                </span>
                <span class="selected-icon-label fw-normal">{{ $value ?: 'Pilih Icon' }}</span>
            </span>
        </button>
        <div class="dropdown-menu p-3 shadow" aria-labelledby="btn-{{ $id }}" style="width: 320px; border: 1px solid #d9dee3; z-index: 1060;">
            <input type="text" class="form-control form-control-sm mb-2 search-icons" placeholder="Cari icon...">
            <div class="row g-2 icon-grid" style="max-height: 240px; overflow-y: auto; margin-left: -4px; margin-right: -4px;">
                <!-- RENDERED BY JS -->
            </div>
        </div>
    </div>
</div>

@once
<style>
    .icon-option-btn {
        width: 100%;
        height: 60px;
        font-size: 9px;
        border: 1px solid #d9dee3 !important;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .icon-option-btn:hover {
        border-color: #696cff !important;
        background-color: rgba(105, 108, 255, 0.08) !important;
        color: #696cff !important;
    }
    .icon-option-btn.active {
        background-color: #696cff !important;
        color: #fff !important;
        border-color: #696cff !important;
    }
</style>
<script>
    if (typeof window.boxiconLists === 'undefined') {
        window.boxiconLists = {
            berita: [
                { class: 'bx-news', label: 'Berita' },
                { class: 'bx-bell', label: 'Pengumuman' },
                { class: 'bx-calendar', label: 'Agenda / Event' },
                { class: 'bx-info-circle', label: 'Informasi' },
                { class: 'bx-image', label: 'Galeri Foto' },
                { class: 'bx-file', label: 'Dokumen' },
                { class: 'bx-run', label: 'Pemuda / Olahraga' },
                { class: 'bx-heart', label: 'Kesehatan' },
                { class: 'bx-book-open', label: 'Pendidikan' },
                { class: 'bx-wallet', label: 'Keuangan' },
                { class: 'bx-group', label: 'Warga / Ormas' },
                { class: 'bx-briefcase', label: 'Ekonomi' },
                { class: 'bx-globe', label: 'Website' },
                { class: 'bx-badge', label: 'Lainnya' }
            ],
            wisata: [
                { class: 'bx-landscape', label: 'Pegunungan/Bukit' },
                { class: 'bx-water', label: 'Air Terjun/Sungai' },
                { class: 'bxs-tree', label: 'Hutan/Kebun' },
                { class: 'bx-compass', label: 'Petualangan' },
                { class: 'bx-camera', label: 'Spot Foto' },
                { class: 'bx-restaurant', label: 'Kuliner' },
                { class: 'bx-map', label: 'Peta Lokasi' },
                { class: 'bx-store', label: 'Belanja' },
                { class: 'bxs-landmark', label: 'Situs Sejarah' },
                { class: 'bx-swim', label: 'Kolam/Air Panas' },
                { class: 'bx-arch', label: 'Monumen/Gapura' },
                { class: 'bx-sun', label: 'Sunset/Sunrise' }
            ],
            fasilitas: [
                { class: 'bx-wifi', label: 'Wi-Fi Gratis' },
                { class: 'bx-water', label: 'Toilet Umum' },
                { class: 'bx-car', label: 'Tempat Parkir' },
                { class: 'bx-restaurant', label: 'Rumah Makan' },
                { class: 'bx-coffee', label: 'Kafe / Kopi' },
                { class: 'bx-bed', label: 'Penginapan' },
                { class: 'bx-store', label: 'Kios / Warung' },
                { class: 'bx-church', label: 'Tempat Ibadah' },
                { class: 'bx-credit-card', label: 'ATM / Pembayaran' },
                { class: 'bx-first-aid', label: 'Pos P3K' },
                { class: 'bx-directions', label: 'Petunjuk Arah' },
                { class: 'bx-trash', label: 'Tempat Sampah' },
                { class: 'bx-shield', label: 'Pos Keamanan' },
                { class: 'bx-plug', label: 'Colokan Listrik' }
            ],
            rute: [
                { class: 'bx-walk', label: 'Jalan Kaki' },
                { class: 'bx-car', label: 'Akses Mobil' },
                { class: 'bx-cycling', label: 'Akses Motor' },
                { class: 'bx-navigation', label: 'Navigasi GPS' },
                { class: 'bx-map-pin', label: 'Titik Transit' },
                { class: 'bx-bus', label: 'Bus Umum' },
                { class: 'bx-train', label: 'Kereta' }
            ],
            pengaduan: [
                { class: 'bx-message-rounded-error', label: 'Laporan Umum' },
                { class: 'bx-wrench', label: 'Jalan / Bangunan' },
                { class: 'bx-bulb', label: 'Lampu / Listrik' },
                { class: 'bx-droplet', label: 'Saluran Air' },
                { class: 'bx-shield-quarter', label: 'Kamtibmas' },
                { class: 'bx-group', label: 'Sosial / Warga' },
                { class: 'bx-trash', label: 'Sampah / Sanitasi' },
                { class: 'bx-heart', label: 'Layanan Medis' },
                { class: 'bx-money', label: 'Bantuan Dana' },
                { class: 'bx-book', label: 'Sekolah' }
            ],
            sosial_media: [
                { class: 'bxl-facebook-circle', label: 'Facebook' },
                { class: 'bxl-instagram', label: 'Instagram' },
                { class: 'bxl-youtube', label: 'YouTube' },
                { class: 'bxl-twitter', label: 'Twitter / X' },
                { class: 'bxl-tiktok', label: 'TikTok' },
                { class: 'bxl-whatsapp', label: 'WhatsApp' },
                { class: 'bxl-telegram', label: 'Telegram' },
                { class: 'bx-globe', label: 'Website' }
            ]
        };
    }
</script>
@endonce

<script>
    (function() {
        const pickerId = '{{ $id }}';
        const pickerType = '{{ $type ?? "berita" }}';
        const container = document.getElementById('picker-container-' + pickerId);
        if (!container) return;

        const input = container.querySelector('input[type="hidden"]');
        const previewBtn = container.querySelector('.dropdown-toggle');
        const iconPreview = container.querySelector('.selected-icon-preview');
        const iconLabel = container.querySelector('.selected-icon-label');
        const grid = container.querySelector('.icon-grid');
        const searchInput = container.querySelector('.search-icons');

        const icons = window.boxiconLists[pickerType] || window.boxiconLists.berita;

        function renderGrid(filterText = '') {
            grid.innerHTML = '';
            icons.forEach(icon => {
                if (filterText && !icon.label.toLowerCase().includes(filterText.toLowerCase()) && !icon.class.toLowerCase().includes(filterText.toLowerCase())) {
                    return;
                }
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-secondary p-2 d-flex flex-column align-items-center justify-content-center icon-option-btn' + (input.value === icon.class ? ' active' : '');
                btn.title = icon.label;
                btn.dataset.icon = icon.class;
                btn.innerHTML = `
                    <i class="bx ${icon.class} fs-3"></i>
                    <span class="text-truncate w-100 mt-1" style="font-size: 8px;">${icon.label}</span>
                `;
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Update value
                    input.value = icon.class;
                    
                    // Update preview button
                    iconPreview.innerHTML = `<i class="bx ${icon.class} fs-4 text-primary"></i>`;
                    iconLabel.textContent = icon.label + ' (' + icon.class + ')';
                    
                    // Remove active from others, add to this
                    grid.querySelectorAll('.icon-option-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    // Close dropdown
                    const dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(previewBtn);
                    dropdownInstance.hide();
                });
                
                const col = document.createElement('div');
                col.className = 'col-3 mb-2';
                col.appendChild(btn);
                grid.appendChild(col);
            });
            
            if (grid.children.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'col-12 text-center py-3 text-muted';
                empty.style.fontSize = '12px';
                empty.textContent = 'Tidak ditemukan';
                grid.appendChild(empty);
            }
        }

        // Initialize grid
        renderGrid();

        // Search handler
        searchInput.addEventListener('input', function() {
            renderGrid(this.value);
        });

        // Set initial label if there is a value
        if (input.value) {
            const found = icons.find(i => i.class === input.value);
            if (found) {
                iconLabel.textContent = found.label + ' (' + found.class + ')';
            } else {
                iconLabel.textContent = input.value;
            }
        }
    })();
</script>

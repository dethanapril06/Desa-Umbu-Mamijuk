<!-- Modal Video Panduan Administrator -->
<div class="modal fade" id="modalVideoPanduan" tabindex="-1" aria-labelledby="modalVideoPanduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light py-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger p-2 rounded-circle">
                        <i class="bx bx-play-circle fs-4 text-white"></i>
                    </span>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" id="modalVideoPanduanLabel">
                            Video Panduan Administrator
                        </h5>
                        <small class="text-muted">Tutorial lengkap penggunaan panel administrasi website desa</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    {{-- 
                        CATATAN: 
                        Ganti 'ID_YOUTUBE_ANDA' di bawah ini dengan ID video YouTube Unlisted Anda.
                        Contoh: Jika link video Anda https://youtu.be/abc123XYZ, maka ID-nya adalah 'abc123XYZ'.
                        Format src: https://www.youtube-nocookie.com/embed/abc123XYZ?enablejsapi=1&rel=0
                    --}}
                    <iframe 
                        id="iframeVideoPanduan"
                        src="https://www.youtube-nocookie.com/embed/XRqKAWW_spk?enablejsapi=1&rel=0" 
                        title="Video Panduan Website Desa" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen
                        style="border: none;">
                    </iframe>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-3 justify-content-between">
                <div class="d-flex align-items-center text-muted small">
                    <i class="bx bx-info-circle me-1 text-primary"></i>
                    <span>Tonton video tutorial atau unduh versi dokumen PDF jika diperlukan.</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.panduan') }}" class="btn btn-sm btn-outline-warning">
                        <i class="bx bx-download me-1"></i> Download PDF
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

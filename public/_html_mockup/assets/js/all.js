// Shared behavior for all pages.
(function () {
  function updateNavbar(scrollY) {
    const nav = document.querySelector('.navbar-desa');
    if (!nav) return;

    nav.style.background = scrollY > 50 ? 'rgba(26, 58, 42, 0.99)' : 'rgba(26, 58, 42, 0.97)';
    nav.style.boxShadow = scrollY > 50 ? '0 4px 30px rgba(0,0,0,0.3)' : 'none';
  }

  function updateReadingProgress(scrollY) {
    const progressBar = document.getElementById('progressBar');
    if (!progressBar) return;

    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = docHeight > 0 ? (scrollY / docHeight) * 100 : 0;
    progressBar.style.width = progress + '%';
  }

  function updateHeroParallax(scrollY) {
    const heroBg = document.getElementById('heroBg');
    if (!heroBg) return;

    heroBg.style.transform = `scale(1.1) translateY(${scrollY * 0.3}px)`;
  }

  function handleScroll() {
    const scrollY = window.scrollY;
    updateNavbar(scrollY);
    updateReadingProgress(scrollY);
    updateHeroParallax(scrollY);
  }

  function initReveal() {
    const reveals = document.querySelectorAll('.reveal');
    if (!reveals.length || !('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
      });
    }, { threshold: 0.1 });

    reveals.forEach(el => observer.observe(el));
  }

  function initFilters() {
    document.querySelectorAll('.filter-pill').forEach(pill => {
      pill.addEventListener('click', function () {
        document.querySelectorAll('.filter-pill').forEach(item => item.classList.remove('active'));
        this.classList.add('active');
      });
    });
  }

  function initLightbox() {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    if (!lightbox || !lightboxImg) return;

    window.openLightbox = function (src) {
      lightboxImg.src = src;
      lightbox.style.display = 'flex';
    };

    window.closeLightbox = function () {
      lightbox.style.display = 'none';
    };

    lightbox.addEventListener('click', event => {
      if (event.target === lightbox) window.closeLightbox();
    });
  }

  function initCopyButton() {
    const copyBtn = document.getElementById('copyBtn');
    if (!copyBtn || !navigator.clipboard) return;

    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(window.location.href).then(() => {
        copyBtn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        copyBtn.style.background = 'var(--green-mist)';
        copyBtn.style.color = 'var(--green-fresh)';

        setTimeout(() => {
          copyBtn.innerHTML = '<i class="fas fa-link"></i> Salin Tautan';
        }, 2000);
      });
    });
  }

  window.addEventListener('scroll', handleScroll);

  document.addEventListener('DOMContentLoaded', () => {
    handleScroll();
    initReveal();
    initFilters();
    initLightbox();
    initCopyButton();
  });
})();

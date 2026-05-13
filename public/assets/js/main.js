// LSW Cars – Frontend Script
(function () {
  'use strict';

  // ===== Mobile-Navigation =================================================
  const navToggle = document.getElementById('navToggle');
  const navMain = document.querySelector('.nav-main');
  if (navToggle && navMain) {
    navToggle.addEventListener('click', () => {
      const open = navMain.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', String(open));
    });
  }

  // ===== Cookie-Banner =====================================================
  const COOKIE_KEY = 'lsw_cookie_consent_v1';
  const banner = document.getElementById('cookieBanner');

  const setBannerOpen = (open) => {
    document.body.classList.toggle('cookie-banner-open', open);
  };

  const readConsent = () => {
    try { return JSON.parse(localStorage.getItem(COOKIE_KEY) || 'null'); }
    catch { return null; }
  };

  const writeConsent = (level) => {
    const data = { level, ts: Date.now() };
    localStorage.setItem(COOKIE_KEY, JSON.stringify(data));
    if (banner) banner.hidden = true;
    setBannerOpen(false);
    document.dispatchEvent(new CustomEvent('lsw:consent', { detail: data }));
  };

  if (banner) {
    if (!readConsent()) {
      banner.hidden = false;
      setBannerOpen(true);
    }
    banner.querySelectorAll('[data-cookie]').forEach(btn => {
      btn.addEventListener('click', () => writeConsent(btn.dataset.cookie));
    });
  }

  const cookieSettingsLink = document.getElementById('openCookieSettings');
  if (cookieSettingsLink && banner) {
    cookieSettingsLink.addEventListener('click', (e) => {
      e.preventDefault();
      banner.hidden = false;
      setBannerOpen(true);
    });
  }

  // ===== Galerie auf Detailseite ==========================================
  const gMain = document.querySelector('.gallery-main img');
  const gThumbs = document.querySelectorAll('.gallery-thumbs button');
  const gCounter = document.querySelector('.gallery-counter');
  const gPrev = document.querySelector('.gallery-nav--prev');
  const gNext = document.querySelector('.gallery-nav--next');
  let gIndex = 0;

  function galleryGoTo(idx) {
    if (!gThumbs.length) return;
    gIndex = (idx + gThumbs.length) % gThumbs.length;
    const src = gThumbs[gIndex].querySelector('img')?.src;
    if (src) gMain.src = src;
    gThumbs.forEach(b => b.classList.remove('active'));
    gThumbs[gIndex].classList.add('active');
    if (gCounter) gCounter.textContent = (gIndex + 1) + ' / ' + gThumbs.length;
    gThumbs[gIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }

  if (gMain && gThumbs.length) {
    gThumbs.forEach((btn, i) => btn.addEventListener('click', () => galleryGoTo(i)));
    if (gPrev) gPrev.addEventListener('click', (e) => { e.stopPropagation(); galleryGoTo(gIndex - 1); });
    if (gNext) gNext.addEventListener('click', (e) => { e.stopPropagation(); galleryGoTo(gIndex + 1); });

    document.addEventListener('keydown', (e) => {
      const lb = document.getElementById('lightbox');
      if (lb && !lb.hidden) {
        if (e.key === 'ArrowLeft') lightboxGoTo(gIndex - 1);
        else if (e.key === 'ArrowRight') lightboxGoTo(gIndex + 1);
        else if (e.key === 'Escape') lb.hidden = true;
        return;
      }
      if (!document.querySelector('.gallery')) return;
      if (e.key === 'ArrowLeft') galleryGoTo(gIndex - 1);
      if (e.key === 'ArrowRight') galleryGoTo(gIndex + 1);
    });
  }

  // ===== Lightbox (Vergrößern) =============================================
  const lightbox = document.getElementById('lightbox');
  const lbImg = lightbox?.querySelector('.lightbox-content img');
  const lbCounter = lightbox?.querySelector('.lightbox-counter');
  const lbPrev = lightbox?.querySelector('.gallery-nav--prev');
  const lbNext = lightbox?.querySelector('.gallery-nav--next');
  const lbClose = lightbox?.querySelector('.lightbox-close');

  function lightboxGoTo(idx) {
    if (!gThumbs.length) return;
    gIndex = (idx + gThumbs.length) % gThumbs.length;
    const src = gThumbs[gIndex].querySelector('img')?.src;
    if (src && lbImg) lbImg.src = src;
    if (lbCounter) lbCounter.textContent = (gIndex + 1) + ' / ' + gThumbs.length;
    galleryGoTo(gIndex);
  }

  function openLightbox(idx) {
    if (!lightbox) return;
    lightboxGoTo(idx);
    lightbox.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.hidden = true;
    document.body.style.overflow = '';
  }

  if (lightbox) {
    lbPrev?.addEventListener('click', () => lightboxGoTo(gIndex - 1));
    lbNext?.addEventListener('click', () => lightboxGoTo(gIndex + 1));
    lbClose?.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
  }

  const zoomBtn = document.getElementById('galleryZoomBtn');
  if (zoomBtn) zoomBtn.addEventListener('click', () => openLightbox(gIndex));

  const mainImg = document.querySelector('.gallery-main');
  if (mainImg) mainImg.addEventListener('click', (e) => {
    if (e.target.closest('.gallery-nav') || e.target.closest('.gallery-action-btn')) return;
    openLightbox(gIndex);
  });

  // ===== Alle Bilder Overlay ===============================================
  const allOverlay = document.getElementById('allImages');
  const allBtn = document.getElementById('galleryAllBtn');
  if (allBtn && allOverlay) {
    allBtn.addEventListener('click', () => {
      allOverlay.hidden = false;
      document.body.style.overflow = 'hidden';
    });
    allOverlay.querySelector('.lightbox-close')?.addEventListener('click', () => {
      allOverlay.hidden = true;
      document.body.style.overflow = '';
    });
    allOverlay.querySelectorAll('.allimages-grid button').forEach(btn => {
      btn.addEventListener('click', () => {
        const idx = parseInt(btn.dataset.index, 10);
        allOverlay.hidden = true;
        openLightbox(idx);
      });
    });
  }

  // ===== Technische Daten Toggle ==========================================
  const tdToggle = document.getElementById('techdataToggle');
  const tdMore = document.querySelector('.techdata-more');
  if (tdToggle && tdMore) {
    tdToggle.addEventListener('click', () => {
      const isHidden = tdMore.hidden;
      tdMore.hidden = !isHidden;
      tdToggle.textContent = isHidden ? 'Weniger anzeigen' : 'Mehr anzeigen';
    });
  }

  // ===== Sanftes Scrollen auf Anchor =======================================
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href');
      if (id.length > 1 && document.querySelector(id)) {
        e.preventDefault();
        document.querySelector(id).scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
})();

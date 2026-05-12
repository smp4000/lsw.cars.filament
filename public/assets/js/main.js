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
  if (gMain && gThumbs.length) {
    gThumbs.forEach(btn => {
      btn.addEventListener('click', () => {
        const src = btn.querySelector('img')?.src;
        if (!src) return;
        gMain.src = src;
        gThumbs.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
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

/**
 * Sulanga Theme Core JavaScript
 */
document.addEventListener('DOMContentLoaded', () => {
  /* sticky nav */
  const nav = document.getElementById('nav');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 60);
    });
    // Check on initial load
    nav.classList.toggle('scrolled', window.scrollY > 60);
  }

  /* mobile menu */
  const navToggle = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');
  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.toggle('open');
    });
    navMenu.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        navMenu.classList.remove('open');
      });
    });
  }

  /* preloader fade */
  const preload = document.getElementById('preload');
  if (preload) {
    window.addEventListener('load', () => {
      setTimeout(() => preload.classList.add('done'), 1000);
    });
    // Fallback if window load already fired
    if (document.readyState === 'complete') {
      setTimeout(() => preload.classList.add('done'), 1000);
    }
  }

  /* scroll reveal triggers */
  const revealElements = document.querySelectorAll('.reveal');
  if (revealElements.length > 0) {
    if ('IntersectionObserver' in window) {
      // Signal header.php that reveals are handled, so it keeps the .reveal-on
      // flag (otherwise the safety fallback would show everything immediately).
      window.__sulangaRevealReady = true;
      const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            e.target.classList.add('in');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
      revealElements.forEach(el => io.observe(el));
    } else {
      // No IntersectionObserver support: reveal everything immediately.
      revealElements.forEach(el => el.classList.add('in'));
    }
  }

  /* amenities image carousel (one image per view) — used on the homepage and
     booking page wherever the amenities section is rendered. */
  const amTrack = document.getElementById('amTrack');
  if (amTrack && amTrack.children.length) {
    const slides = amTrack.children;
    const amPrev = document.getElementById('amPrev');
    const amNext = document.getElementById('amNext');
    const amDots = document.getElementById('amDots');
    let amIdx = 0;

    if (amDots) {
      for (let i = 0; i < slides.length; i++) {
        const dot = document.createElement('button');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', (function (n) { return function () { amGo(n); }; })(i));
        amDots.appendChild(dot);
      }
    }
    function amGo(i) {
      amIdx = (i + slides.length) % slides.length;
      amTrack.style.transform = 'translateX(-' + (amIdx * 100) + '%)';
      if (amDots) {
        const ds = amDots.children;
        for (let j = 0; j < ds.length; j++) ds[j].classList.toggle('active', j === amIdx);
      }
    }
    if (amPrev) amPrev.addEventListener('click', () => amGo(amIdx - 1));
    if (amNext) amNext.addEventListener('click', () => amGo(amIdx + 1));
    if (slides.length > 1) setInterval(() => amGo(amIdx + 1), 5000);
  }
});

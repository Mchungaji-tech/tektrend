/**
 * TEKTREND SOFTWARES · PLATFORM INTERACTION ENGINE
 * High-Performance Digital System · Eldoret, Kenya
 * Features: Lenis Smooth Inertia Scroll, GSAP ScrollTrigger Pinned Horizontal Showcase,
 * Dynamic Background Color Shifts, 3D Perspective Letter Tilt, Magnetic Fluid Cursor,
 * Floating Service Preview, Responsive Case Study Runner & Fullscreen Menu.
 */

(function () {
  'use strict';

  // ==========================================================================
  // 1. LENIS SMOOTH MOMENTUM SCROLLING
  // ==========================================================================
  let lenis = null;
  if (typeof Lenis !== 'undefined') {
    lenis = new Lenis({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      orientation: 'vertical',
      gestureOrientation: 'vertical',
      smoothWheel: true,
      wheelMultiplier: 1.0,
      touchMultiplier: 1.5,
      infinite: false
    });

    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // Provide global access so other interactions can pause/resume scroll
    window.tektrendLenis = lenis;
  }

  // ==========================================================================
  // 2. GSAP & SCROLLTRIGGER ANIMATION SYSTEM
  // ==========================================================================
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    // Sync Lenis scroll with GSAP ScrollTrigger
    if (lenis) {
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
      });
      gsap.ticker.lagSmoothing(0);
    }

    // --- A. PINNED HORIZONTAL SHOWCASE (AKARU HOME SHOWCASE) ---
    const pinWrap = document.getElementById('akarShowcase');
    const stickyWrap = pinWrap ? pinWrap.querySelector('.akar-showcase-sticky') : null;
    const reel = document.getElementById('akarShowcaseReel');
    const counterEl = document.getElementById('akarShowcaseCounter');

    if (pinWrap && stickyWrap && reel) {
      const initShowcaseScroll = () => {
        if (window.innerWidth > 1024) {
          const reelWidth = reel.scrollWidth;
          const windowWidth = window.innerWidth;
          const scrollDistance = Math.max(0, reelWidth - windowWidth + 140);

          if (scrollDistance > 0) {
            const cards = reel.querySelectorAll('.akar-project-card');
            const paletteBgMaps = {
              terracotta: {
                terra: 'rgba(228, 147, 102, 0.14)',
                green: 'rgba(121, 142, 123, 0.16)',
                pink:  'rgba(182, 146, 161, 0.16)',
                blue:  'rgba(139, 180, 201, 0.16)'
              },
              emerald: {
                terra: 'rgba(16, 185, 129, 0.15)',
                green: 'rgba(5, 150, 105, 0.16)',
                pink:  'rgba(245, 158, 11, 0.15)',
                blue:  'rgba(6, 182, 212, 0.16)'
              },
              cyberpunk: {
                terra: 'rgba(168, 85, 247, 0.16)',
                green: 'rgba(6, 182, 212, 0.16)',
                pink:  'rgba(244, 63, 94, 0.16)',
                blue:  'rgba(59, 130, 246, 0.16)'
              },
              cobalt: {
                terra: 'rgba(59, 130, 246, 0.16)',
                green: 'rgba(16, 185, 129, 0.15)',
                pink:  'rgba(99, 102, 241, 0.16)',
                blue:  'rgba(14, 165, 233, 0.16)'
              },
              sunset: {
                terra: 'rgba(249, 115, 22, 0.16)',
                green: 'rgba(234, 179, 8, 0.16)',
                pink:  'rgba(239, 68, 68, 0.16)',
                blue:  'rgba(236, 72, 153, 0.16)'
              },
              monochrome: {
                terra: 'rgba(255, 255, 255, 0.08)',
                green: 'rgba(148, 163, 184, 0.12)',
                pink:  'rgba(100, 116, 139, 0.12)',
                blue:  'rgba(203, 213, 225, 0.12)'
              },
              alabaster: {
                terra: 'rgba(217, 119, 6, 0.15)',
                green: 'rgba(5, 150, 105, 0.15)',
                pink:  'rgba(219, 39, 119, 0.15)',
                blue:  'rgba(37, 99, 235, 0.15)'
              }
            };

            gsap.to(reel, {
              x: -scrollDistance,
              ease: 'none',
              scrollTrigger: {
                id: 'showcaseHorizontal',
                trigger: pinWrap,
                pin: stickyWrap,
                start: 'top top',
                end: () => '+=' + (scrollDistance * 1.35),
                scrub: 0.8,
                invalidateOnRefresh: true,
                onUpdate: (self) => {
                  const progress = self.progress;
                  const totalCards = cards.length;
                  const activeIndex = Math.min(
                    totalCards - 1,
                    Math.floor(progress * totalCards)
                  );

                  // Update dynamic counter (e.g. 01 / 06)
                  if (counterEl && cards[activeIndex]) {
                    const idxStr = cards[activeIndex].getAttribute('data-index') || `0${activeIndex + 1} / 06`;
                    counterEl.textContent = idxStr;
                  }

                  // Dynamic subtle background tint morphing
                  const activeCard = cards[activeIndex];
                  if (activeCard && pinWrap) {
                    const currentPalette = document.documentElement.getAttribute('data-palette') || 'terracotta';
                    const activeBgMap = paletteBgMaps[currentPalette] || paletteBgMaps.terracotta;
                    const colorKey = activeCard.getAttribute('data-color') || 'terra';
                    pinWrap.style.backgroundColor = activeBgMap[colorKey] || '';
                  }
                }
              }
            });

            // Parallax image motion inside cards
            cards.forEach((card) => {
              const img = card.querySelector('.akar-card-media img');
              if (img) {
                gsap.fromTo(img,
                  { x: 35, scale: 1.08 },
                  {
                    x: -35,
                    scale: 1.0,
                    ease: 'none',
                    scrollTrigger: {
                      trigger: pinWrap,
                      start: 'top top',
                      end: () => '+=' + (scrollDistance * 1.35),
                      scrub: true
                    }
                  }
                );
              }
            });
          }
        }
      };

      initShowcaseScroll();

      // Refresh on window resize
      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          ScrollTrigger.getById('showcaseHorizontal')?.kill();
          initShowcaseScroll();
          ScrollTrigger.refresh();
        }, 200);
      });
    }

    // --- B. HERO MONUMENTAL TYPOGRAPHY: 3D MOUSE TILT & SCROLL PARALLAX ---
    const stage = document.getElementById('akarLetterStage');
    const letters = document.querySelectorAll('.akar-letter');

    if (stage && letters.length) {
      // Scroll parallax lifting letters upward with staggered depth
      letters.forEach((letter, i) => {
        const lift = 35 + (i % 4) * 20;
        gsap.to(letter, {
          y: -lift,
          ease: 'none',
          scrollTrigger: {
            trigger: stage,
            start: 'top top',
            end: 'bottom top',
            scrub: true
          }
        });
      });

      // 3D perspective mouse tilt
      stage.addEventListener('mousemove', (e) => {
        const rect = stage.getBoundingClientRect();
        const normX = (e.clientX - rect.left) / rect.width - 0.5;
        const normY = (e.clientY - rect.top) / rect.height - 0.5;

        gsap.to(letters, {
          rotateY: normX * 18,
          rotateX: -normY * 18,
          transformPerspective: 900,
          stagger: 0.02,
          duration: 0.6,
          ease: 'power2.out'
        });
      });

      stage.addEventListener('mouseleave', () => {
        gsap.to(letters, {
          rotateY: 0,
          rotateX: 0,
          duration: 0.8,
          ease: 'power2.out'
        });
      });
    }

    // --- C. SCROLL-TRIGGERED STAGGER REVEALS ---
    gsap.utils.toArray('.akar-service-row').forEach((row, i) => {
      gsap.from(row, {
        opacity: 0,
        y: 40,
        duration: 0.85,
        delay: i * 0.08,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: row,
          start: 'top 88%',
          toggleActions: 'play none none none'
        }
      });
    });

    gsap.utils.toArray('.akar-metric-box').forEach((box, i) => {
      gsap.from(box, {
        opacity: 0,
        y: 35,
        duration: 0.8,
        delay: i * 0.1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: box,
          start: 'top 85%',
          toggleActions: 'play none none none'
        }
      });
    });
  }

  // ==========================================================================
  // 3. FLUID ELASTIC MAGNETIC CURSOR
  // ==========================================================================
  const cursor = document.getElementById('akarCursor');
  if (cursor && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    let mouseX = -100, mouseY = -100;
    let cursorX = -100, cursorY = -100;

    window.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursor.classList.remove('--hidden');
    });

    window.addEventListener('mouseleave', () => {
      cursor.classList.add('--hidden');
    });

    // Spring damping interpolation
    function renderCursor() {
      cursorX += (mouseX - cursorX) * 0.18;
      cursorY += (mouseY - cursorY) * 0.18;
      cursor.style.left = `${cursorX}px`;
      cursor.style.top = `${cursorY}px`;
      requestAnimationFrame(renderCursor);
    }
    requestAnimationFrame(renderCursor);

    // Expand on Project Cards & Showcase Items
    const cardSelectors = '.akar-project-card, .portfolio-grid-card, .akar-nav-teaser-box';
    document.querySelectorAll(cardSelectors).forEach((card) => {
      card.addEventListener('mouseenter', () => cursor.classList.add('--hover-card'));
      card.addEventListener('mouseleave', () => cursor.classList.remove('--hover-card'));
    });

    // Ring on Interactive Links & Buttons
    const linkSelectors = 'a, button, .akar-service-row, .akar-featured-pill, .akar-filter-btn, .akar-device-btn';
    document.querySelectorAll(linkSelectors).forEach((link) => {
      link.addEventListener('mouseenter', () => {
        if (!cursor.classList.contains('--hover-card')) {
          cursor.classList.add('--hover-link');
        }
      });
      link.addEventListener('mouseleave', () => {
        cursor.classList.remove('--hover-link');
      });
    });
  }

  // ==========================================================================
  // 4. FLOATING SERVICES HOVER PREVIEW IMAGE
  // ==========================================================================
  const floatImgBox = document.getElementById('akarFloatingServiceImg');
  const serviceRows = document.querySelectorAll('.akar-service-row');

  if (floatImgBox && serviceRows.length && window.matchMedia('(hover: hover)').matches) {
    const floatImg = floatImgBox.querySelector('img');
    let targetX = 0, targetY = 0;
    let floatX = 0, floatY = 0;

    window.addEventListener('mousemove', (e) => {
      targetX = e.clientX + 30;
      targetY = e.clientY - 90;
    });

    function renderFloatingImg() {
      floatX += (targetX - floatX) * 0.12;
      floatY += (targetY - floatY) * 0.12;
      floatImgBox.style.left = `${floatX}px`;
      floatImgBox.style.top = `${floatY}px`;
      requestAnimationFrame(renderFloatingImg);
    }
    requestAnimationFrame(renderFloatingImg);

    serviceRows.forEach((row) => {
      row.addEventListener('mouseenter', () => {
        const imgSrc = row.getAttribute('data-img');
        if (imgSrc && floatImg) {
          floatImg.src = imgSrc;
          floatImgBox.classList.add('--visible');
        }
      });
      row.addEventListener('mouseleave', () => {
        floatImgBox.classList.remove('--visible');
      });
    });
  }

  // ==========================================================================
  // 5. FULLSCREEN CLIP-PATH NAVIGATION MENU
  // ==========================================================================
  const burgerBtn = document.getElementById('akarBurgerBtn');
  const navOverlay = document.getElementById('akarNavOverlay');
  const teaserImg = document.getElementById('akarTeaserImg');
  const teaserBadge = document.getElementById('akarTeaserBadge');

  if (burgerBtn && navOverlay) {
    burgerBtn.addEventListener('click', () => {
      const isOpen = navOverlay.classList.toggle('--open');
      burgerBtn.classList.toggle('--open', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
      if (lenis) {
        if (isOpen) lenis.stop();
        else lenis.start();
      }
    });

    // Close menu when clicking any overlay link
    navOverlay.querySelectorAll('.akar-nav-link').forEach((link) => {
      link.addEventListener('click', () => {
        navOverlay.classList.remove('--open');
        burgerBtn.classList.remove('--open');
        document.body.style.overflow = '';
        if (lenis) lenis.start();
      });
    });

    // Dynamic Teaser Updates on Hovering Menu Items
    const menuTeasers = {
      'home': { img: 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80', badge: 'Nexus Commercial ERP' },
      'projects': { img: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', badge: 'Selected Architectures' },
      'services': { img: 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80', badge: 'Systems Capabilities' },
      'demos': { img: 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80', badge: 'LuxeCart E-Commerce' },
      'consultation': { img: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80', badge: 'Book Zoom 45-Min' },
      'portal': { img: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80', badge: 'Virtual Headquarters' }
    };

    navOverlay.querySelectorAll('[data-teaser]').forEach((el) => {
      el.addEventListener('mouseenter', () => {
        const key = el.getAttribute('data-teaser');
        if (menuTeasers[key] && teaserImg && teaserBadge) {
          teaserImg.src = menuTeasers[key].img;
          teaserBadge.textContent = menuTeasers[key].badge;
        }
      });
    });
  }

  // ==========================================================================
  // 6. IMMERSIVE CASE STUDY DRAWER & MULTI-DEVICE PROTOTYPE RUNNER
  // ==========================================================================
  const caseDrawer = document.getElementById('akarCaseDrawer');
  const drawerIframe = document.getElementById('akarDrawerIframe');
  const drawerTitle = document.getElementById('akarDrawerTitle');
  const drawerCategory = document.getElementById('akarDrawerCategory');
  const drawerDesc = document.getElementById('akarDrawerDesc');
  const drawerTech = document.getElementById('akarDrawerTech');
  const drawerPrice = document.getElementById('akarDrawerPrice');
  const drawerExternalLink = document.getElementById('akarDrawerExternalLink');
  const frameWrapper = document.getElementById('akarFrameWrapper');
  const closeDrawerBtn = document.getElementById('akarCloseDrawer');
  const backDrawerBtn = document.getElementById('akarBackDrawer');

  window.openAkaruCaseStudy = function (projectData) {
    if (!caseDrawer) return;

    if (drawerTitle) drawerTitle.textContent = projectData.title || 'Project Case Study';
    if (drawerCategory) drawerCategory.textContent = projectData.category || 'Turnkey System';
    if (drawerDesc) drawerDesc.textContent = projectData.short_desc || projectData.desc || 'High-performance production architecture engineered by Tek Trend.';
    if (drawerTech) drawerTech.textContent = projectData.tech || 'PHP 8, HTML5, CSS3';
    if (drawerPrice) drawerPrice.textContent = projectData.price ? `$${projectData.price}` : '$49';

    const targetUrl = projectData.demo_url || projectData.url || '';
    if (drawerExternalLink && targetUrl) {
      drawerExternalLink.href = targetUrl;
    }

    if (drawerIframe && targetUrl) {
      drawerIframe.src = targetUrl;
    }

    if (frameWrapper) {
      frameWrapper.className = 'akar-live-frame-wrapper --desktop';
    }

    caseDrawer.classList.add('--active');
    document.body.style.overflow = 'hidden';
    if (lenis) lenis.stop();
  };

  function closeCaseDrawer() {
    if (!caseDrawer) return;
    caseDrawer.classList.remove('--active');
    document.body.style.overflow = '';
    if (lenis) lenis.start();
    if (drawerIframe) {
      drawerIframe.src = 'about:blank';
    }
  }

  if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeCaseDrawer);
  if (backDrawerBtn) backDrawerBtn.addEventListener('click', closeCaseDrawer);

  // Device Responsive Switcher (Desktop / Tablet / Mobile)
  document.querySelectorAll('.akar-device-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.akar-device-btn').forEach((b) => b.classList.remove('--active'));
      btn.classList.add('--active');
      const mode = btn.getAttribute('data-device');
      if (frameWrapper) {
        frameWrapper.className = `akar-live-frame-wrapper --${mode}`;
      }
    });
  });

  // ESC key closes drawer & menu
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeCaseDrawer();
      if (navOverlay && navOverlay.classList.contains('--open')) {
        navOverlay.classList.remove('--open');
        if (burgerBtn) burgerBtn.classList.remove('--open');
        document.body.style.overflow = '';
        if (lenis) lenis.start();
      }
    }
  });

  // ==========================================================================
  // 7. PORTFOLIO / LIVE DEMOS FILTERING
  // ==========================================================================
  window.filterAkaruDemos = function (category) {
    const items = document.querySelectorAll('.akar-portfolio-item');
    const buttons = document.querySelectorAll('.akar-filter-btn');

    buttons.forEach((btn) => {
      btn.classList.toggle('--active', btn.getAttribute('data-filter') === category);
    });

    items.forEach((item) => {
      const itemCats = item.getAttribute('data-category') || '';
      if (category === 'all' || itemCats.includes(category)) {
        item.style.display = 'flex';
      } else {
        item.style.display = 'none';
      }
    });

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.refresh();
    }
  };

  // ==========================================================================
  // 8. THEME & COLOR PALETTE SWITCHER (MULTI-PALETTE ARCHITECTURE)
  // ==========================================================================
  window.toggleAkaruTheme = function () {
    const current = document.documentElement.getAttribute('data-theme') || 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('tektrend_theme', next);
    updateThemeIcon(next);
  };

  function updateThemeIcon(theme) {
    const icon = document.getElementById('akarThemeIcon');
    if (icon) {
      icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
  }

  window.setAkaruPalette = function (paletteName) {
    const validPalettes = ['terracotta', 'emerald', 'cyberpunk', 'cobalt', 'sunset', 'monochrome', 'alabaster'];
    if (!validPalettes.includes(paletteName)) {
      paletteName = 'terracotta';
    }
    document.documentElement.setAttribute('data-palette', paletteName);
    localStorage.setItem('tektrend_palette', paletteName);
    updatePaletteUI(paletteName);
  };

  function updatePaletteUI(palette) {
    document.querySelectorAll('.akar-palette-option').forEach(btn => {
      const p = btn.getAttribute('data-palette-target');
      if (p === palette) {
        btn.classList.add('--active');
      } else {
        btn.classList.remove('--active');
      }
    });
  }

  window.togglePaletteModal = function () {
    const modal = document.getElementById('akarPaletteModal');
    if (modal) {
      modal.classList.toggle('--open');
    }
  };

  window.closePaletteModal = function () {
    const modal = document.getElementById('akarPaletteModal');
    if (modal) {
      modal.classList.remove('--open');
    }
  };

  // ==========================================================================
  // 9. INVESTOR RELATIONS MODAL & TIER SELECTION
  // ==========================================================================
  window.openInvestModal = function () {
    const modal = document.getElementById('akarInvestModal');
    if (modal) {
      modal.classList.add('--open');
      document.body.style.overflow = 'hidden';
      if (lenis) lenis.stop();
    }
  };

  window.closeInvestModal = function () {
    const modal = document.getElementById('akarInvestModal');
    if (modal) {
      modal.classList.remove('--open');
      document.body.style.overflow = '';
      if (lenis) lenis.start();
    }
  };

  window.selectInvestTier = function (amount, name) {
    const amountInput = document.getElementById('investAmountInput');
    if (amountInput) amountInput.value = amount;
    document.querySelectorAll('.akar-tier-btn').forEach(b => {
      b.classList.toggle('--active', b.getAttribute('data-tier') === name);
    });
  };

  // Close modals on Escape or clicking outside
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      window.closePaletteModal();
      window.closeInvestModal();
    }
  });

  document.addEventListener('click', function (e) {
    const paletteModal = document.getElementById('akarPaletteModal');
    const paletteToggleBtn = e.target.closest('.akar-palette-pill');
    if (paletteModal && paletteModal.classList.contains('--open')) {
      if (!paletteModal.contains(e.target) && !paletteToggleBtn) {
        window.closePaletteModal();
      }
    }

    const investModalOverlay = document.getElementById('akarInvestModal');
    if (investModalOverlay && investModalOverlay.classList.contains('--open')) {
      if (e.target === investModalOverlay) {
        window.closeInvestModal();
      }
    }
  });

  // Init theme & palette on page ready
  const savedTheme = localStorage.getItem('tektrend_theme') || 'dark';
  document.documentElement.setAttribute('data-theme', savedTheme);
  updateThemeIcon(savedTheme);

  const savedPalette = localStorage.getItem('tektrend_palette') || 'terracotta';
  document.documentElement.setAttribute('data-palette', savedPalette);
  updatePaletteUI(savedPalette);

})();

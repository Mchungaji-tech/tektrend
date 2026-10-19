/* ============================================================
   MM Motion Engine — award-site grade motion layer (dependency free)
   Inspired by obys.agency / binaery / paisana style interactions:
   preloader counter, inertia scroll, split-text reveals, image
   parallax + velocity skew, magnetic buttons, marquee, custom
   cursor, horizontal pinned galleries, film grain, scroll progress.
   Usage: <link rel="stylesheet" href="enhance.css">
          <script src="enhance.js"></script>   (in <head>, no defer)
          optional: window.MM_OPTIONS = {...}  before the script tag
   ============================================================ */
(function () {
  'use strict';

  var html = document.documentElement;
  var OPT = window.MM_OPTIONS || {};
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var touch = window.matchMedia('(hover: none), (pointer: coarse)').matches;

  function opt(name, fallback) {
    return OPT[name] === undefined ? fallback : OPT[name];
  }
  var enabled = !reduced;
  var useCursor = opt('cursor', true) && !touch;
  var usePreloader = opt('preloader', true);
  var useSmooth = opt('smooth', true) && !touch;
  var useGrain = opt('grain', true);
  var useParallax = opt('parallax', true);
  var useSkew = opt('skew', true);
  var useSplit = opt('split', true);
  var useMagnetic = opt('magnetic', !touch);
  var useProgress = opt('progress', true);

  /* ---------- global image fallback: if a photo fails / is network-
     blocked (e.g. ERR_BLOCKED_BY_ORB on restricted networks), swap it
     for a self-contained gradient tile instead of a browser "blocked"
     placeholder. Keeps demos looking intentional with zero deps. ---------- */
  (function imageErrorFallback() {
    var accent = (OPT.accent && /^[#a-zA-Z0-9]+$/.test(OPT.accent)) ? OPT.accent : '#c9a87c';
    var brand = String(OPT.brand || 'TekTrend').replace(/[^a-zA-Z0-9 &._-]/g, '').slice(0, 22) || 'TekTrend';
    var tile = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(
      "<svg xmlns='http://www.w3.org/2000/svg' width='1200' height='800'>" +
      "<defs><linearGradient id='g' x1='0' y1='0' x2='1' y2='1'>" +
      "<stop offset='0' stop-color='" + accent + "'/>" +
      "<stop offset='1' stop-color='#141414'/></linearGradient></defs>" +
      "<rect width='1200' height='800' fill='url(#g)'/>" +
      "<circle cx='870' cy='250' r='210' fill='rgba(255,255,255,0.08)'/>" +
      "<text x='60' y='720' font-family='Georgia,serif' font-size='44' fill='rgba(255,255,255,0.9)'>" + brand + "</text>" +
      "</svg>"
    );
    function swap(img) {
      if (!img || img.getAttribute('data-mm-fb') === '1') return;
      img.setAttribute('data-mm-fb', '1');
      img.src = tile;
    }
    // capture-phase error listener catches <img> load failures
    window.addEventListener('error', function (e) {
      var t = e && e.target;
      if (t && t.tagName === 'IMG') swap(t);
    }, true);
    // sweep images that finished already-failed before listener attached
    function sweep() {
      var imgs = document.images;
      for (var i = 0; i < imgs.length; i++) {
        var im = imgs[i];
        if (im.complete && im.naturalWidth === 0 && im.getAttribute('data-mm-fb') !== '1') {
          if (im.currentSrc && im.currentSrc.indexOf('data:') === 0) continue;
          swap(im);
        }
      }
    }
    if (document.readyState === 'complete') sweep();
    else window.addEventListener('load', sweep);
  })();

  /* ---------- detect light/dark page for overlay colors ---------- */
  function bodyIsDark() {
    try {
      var bg = getComputedStyle(document.body || html).backgroundColor;
      var m = bg.match(/(\d+)\D+(\d+)\D+(\d+)/);
      if (!m) return true;
      var lum = (0.299 * +m[1] + 0.587 * +m[2] + 0.114 * +m[3]) / 255;
      return lum < 0.5;
    } catch (e) { return true; }
  }

  /* ================= Preloader (created before first paint) ========== */
  var pre = null;
  if (usePreloader && enabled) {
    html.classList.add('mm-preloading');
    pre = document.createElement('div');
    pre.className = 'mm-preload';
    var brand = (OPT.brand || document.title.split('·')[0].split('|')[0].trim() || 'MM');
    pre.innerHTML =
      '<div class="mm-preload__bar"><span class="mm-preload__brand"></span>' +
      '<span class="mm-preload__num">0</span></div>' +
      '<div class="mm-preload__pct"><i></i></div>';
    pre.querySelector('.mm-preload__brand').textContent = brand;
    var inject = function () { document.body.appendChild(pre); };
    if (document.body) inject(); else document.addEventListener('DOMContentLoaded', inject);
  }

  /* ================= state ================= */
  var scroll = { current: 0, target: 0, max: 0, velocity: 0, locked: false };
  var rafs = [];
  var parallaxEls = [];
  var splitObserved = [];

  function clamp(v, a, b) { return Math.min(b, Math.max(a, v)); }
  function lerp(a, b, t) { return a + (b - a) * t; }

  /* ================= Inertia (Lenis-style window) scroll ============== */
  function initSmoothScroll() {
    if (!useSmooth || !enabled) return;
    // do not fight layouts that scroll internally
    var bOver = getComputedStyle(document.body).overflow;
    if (bOver.indexOf('hidden') === 0 && getComputedStyle(document.body).height === '100vh') return;

    scroll.current = scroll.target = window.scrollY;
    var easing = opt('smoothEase', 0.085);
    var wheelAccum = 0;

    window.addEventListener('wheel', function (e) {
      if (scroll.locked) return;
      e.preventDefault();
      scroll.target = clamp(scroll.target + e.deltaY * (e.deltaMode === 1 ? 33 : 1), 0, scroll.max);
    }, { passive: false });

    // sync when user uses scrollbar, keyboard, anchors or touch
    var syncing = false;
    window.addEventListener('scroll', function () {
      if (syncing) return;
      if (Math.abs(window.scrollY - scroll.current) > 2) {
        scroll.target = scroll.current = window.scrollY;
      }
    }, { passive: true });

    function frame() {
      scroll.max = document.body.scrollHeight - window.innerHeight;
      var prev = scroll.current;
      if (Math.abs(scroll.target - scroll.current) > 0.4) {
        syncing = true;
        scroll.current = lerp(scroll.current, scroll.target, easing);
        window.scrollTo(0, scroll.current);
        syncing = false;
      }
      scroll.velocity = scroll.current - prev;
      rafs.forEach(function (f) { f(); });
      requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);

    // smooth anchor jumps
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[href^="#"]');
      if (!a) return;
      var id = a.getAttribute('href').slice(1);
      if (!id) return;
      var t = document.getElementById(id);
      if (!t) return;
      e.preventDefault();
      scroll.target = clamp(t.getBoundingClientRect().top + scroll.current, 0, scroll.max);
    });
  }
  // fallback ticker when smooth scroll disabled — still need velocity for fx
  function initFallbackTicker() {
    if (useSmooth && enabled) return;
    var prev = window.scrollY;
    function frame() {
      scroll.current = scroll.target = window.scrollY;
      scroll.velocity = window.scrollY - prev;
      prev = window.scrollY;
      rafs.forEach(function (f) { f(); });
      requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }

  /* ================= Split-text word reveal =========================== */
  function splitInto(node, host, idx) {
    if (node.nodeType === 3) {
      var toks = node.textContent.match(/\s+|\S+/g) || [];
      toks.forEach(function (tok) {
        if (/^\s+$/.test(tok)) { host.appendChild(document.createTextNode(' ')); return; }
        var outer = document.createElement('span');
        outer.className = 'mm-word';
        var inner = document.createElement('span');
        inner.className = 'mm-word__i';
        inner.textContent = tok;
        inner.style.transitionDelay = (idx.i++ * 0.05) + 's';
        outer.appendChild(inner);
        host.appendChild(outer);
      });
    } else if (node.nodeName === 'BR') {
      host.appendChild(document.createElement('br'));
    } else if (node.nodeType === 1) {
      var cl = node.cloneNode(false); // keep element + attrs/classes, drop its children for re-split
      Array.prototype.slice.call(node.childNodes).forEach(function (c) { splitInto(c, cl, idx); });
      host.appendChild(cl);
    }
  }
  function splitWords(el) {
    if (el.closest('.mm-preload') || el.dataset.mmDone) return;
    el.dataset.mmDone = '1';
    var kids = Array.prototype.slice.call(el.childNodes);
    var frag = document.createDocumentFragment();
    var idx = { i: 0 };
    kids.forEach(function (n) { splitInto(n, frag, idx); });
    el.textContent = '';
    el.appendChild(frag);
    splitObserved.push(el);
  }
  function initSplit() {
    if (!useSplit || !enabled) return;
    var sel = OPT.splitSelector || 'h1, h2[data-mm-split], .mm-split, [data-mm-split]';
    document.querySelectorAll(sel).forEach(splitWords);
    // late-added nodes
    var mo = new MutationObserver(function (ms) {
      ms.forEach(function (m) {
        m.addedNodes.forEach(function (n) {
          if (n.nodeType === 1 && n.matches && n.matches(sel)) splitWords(n);
        });
      });
    });
    mo.observe(document.body, { childList: true, subtree: true });
  }

  /* ================= Intersection reveal ============================== */
  function initReveals() {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target;
        el.classList.add('mm-in');
        if (el.classList.contains('mm-word-host') || el.dataset.mmSplit) {}
        io.unobserve(el);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    // split hosts animate when visible
    splitObserved.forEach(function (el) { io.observe(el); });
    // opt-in reveals
    document.querySelectorAll('[data-mm-reveal]').forEach(function (el) { io.observe(el); });
    // stagger groups
    document.querySelectorAll('[data-mm-stagger]').forEach(function (g) {
      var ioG = new IntersectionObserver(function (es) {
        es.forEach(function (en) {
          if (!en.isIntersecting) return;
          Array.prototype.forEach.call(en.target.children, function (c, i) {
            c.style.transitionDelay = (i * 0.08) + 's';
            c.classList.add('mm-in');
          });
          ioG.unobserve(en.target);
        });
      }, { threshold: 0.1 });
      ioG.observe(g);
    });
    window.__mmIO = io;
  }

  /* ================= Parallax + velocity skew ========================= */
  function initParallax() {
    if ((!useParallax && !useSkew) || !enabled) return;
    var sel = OPT.parallaxSelector || 'img';
    document.querySelectorAll(sel).forEach(function (img) {
      if (img.closest('.mm-preload')) return;
      if (img.offsetWidth < 140 && img.clientHeight < 140) return;
      if (img.dataset.mmNoPar) return;
      img.classList.add('mm-par');
      parallaxEls.push({
        el: img,
        speed: parseFloat(img.getAttribute('data-mm-speed') || (img.offsetWidth > 700 ? 0.06 : 0.12)),
        skew: img.hasAttribute('data-mm-skew') || useSkew
      });
    });
  }
  function parallaxFrame() {
    if (!parallaxEls.length) return;
    var vh = window.innerHeight;
    for (var i = 0; i < parallaxEls.length; i++) {
      var p = parallaxEls[i];
      var r = p.el.getBoundingClientRect();
      if (r.bottom < -120 || r.top > vh + 120) continue;
      var center = r.top + r.height / 2 - vh / 2;
      var y = useParallax ? -center * p.speed : 0;
      var sk = useSkew && p.skew ? clamp(scroll.velocity * 0.16, -5, 5) : 0;
      p.el.style.transform = 'translate3d(0,' + y.toFixed(2) + 'px,0)' +
        (sk ? ' skewY(' + sk.toFixed(2) + 'deg)' : '');
    }
  }

  /* ================= Marquee ========================================== */
  function initMarquee() {
    // auto-promote existing ticker strips on the page
    document.querySelectorAll('.marquee, .marquee-track, [data-marquee]').forEach(function (el) {
      if (el.classList.contains('mm-marquee') || el.closest('.mm-marquee')) return;
      var txt = el.textContent.trim();
      if (!txt) return;
      var wrap = document.createElement('div');
      wrap.className = 'mm-marquee';
      wrap.innerHTML = '<div class="mm-marquee__inner"></div>';
      wrap.firstChild.textContent = txt + '  •  ';
      el.classList.add('mm-marquee-host');
      el.style.overflow = 'hidden';
      var s = el.style;
      s.whiteSpace = 'nowrap';
      s.display = 'flex';
      s.position = 'relative';
      el.appendChild(wrap.firstChild);
      var clone = wrap.firstChild.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      clone.className = 'mm-marquee__inner mm-marquee__copy';
      el.appendChild(clone);
    });
    document.querySelectorAll('.mm-marquee').forEach(function (mq) {
      if (mq.dataset.mmDone) return;
      mq.dataset.mmDone = '1';
      var inner = mq.querySelector('.mm-marquee__inner');
      if (!inner) {
        inner = document.createElement('div');
        inner.className = 'mm-marquee__inner';
        while (mq.firstChild) inner.appendChild(mq.firstChild);
        mq.appendChild(inner);
      }
      inner.setAttribute('aria-hidden', 'false');
      var clone = inner.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      clone.className = 'mm-marquee__inner mm-marquee__copy';
      mq.appendChild(clone);
      var dur = (mq.getAttribute('data-mm-duration') || 22) + 's';
      inner.style.animationDuration = dur;
      clone.style.animationDuration = dur;
      if (mq.hasAttribute('data-mm-reverse')) {
        inner.style.animationDirection = 'reverse';
        clone.style.animationDirection = 'reverse';
      }
    });
  }

  /* ================= Magnetic ========================================== */
  function initMagnetic() {
    if (!useMagnetic || !enabled) return;
    var sel = OPT.magneticSelector || '[data-mm-magnetic], .nav-cta, .btn, .cta-btn, button:not(.mm-nomagnetic)';
    document.querySelectorAll(sel).forEach(function (el) {
      if (el.dataset.mmMag) return;
      el.dataset.mmMag = '1';
      var strength = parseFloat(el.getAttribute('data-mm-strength') || 0.32);
      el.addEventListener('mousemove', function (e) {
        var r = el.getBoundingClientRect();
        var x = (e.clientX - r.left - r.width / 2) * strength;
        var y = (e.clientY - r.top - r.height / 2) * strength;
        el.style.transform = 'translate(' + x.toFixed(1) + 'px,' + y.toFixed(1) + 'px)';
        el.style.transition = 'transform .18s ease-out';
      });
      el.addEventListener('mouseleave', function () {
        el.style.transition = 'transform .5s cubic-bezier(.23,1,.32,1)';
        el.style.transform = '';
      });
    });
  }

  /* ================= Custom cursor ==================================== */
  function initCursor() {
    if (!useCursor || !enabled) return;
    var dot = document.createElement('div'); dot.className = 'mm-cursor';
    var ring = document.createElement('div'); ring.className = 'mm-cursor-ring';
    document.body.appendChild(dot); document.body.appendChild(ring);
    var mx = innerWidth / 2, my = innerHeight / 2, rx = mx, ry = my;
    window.addEventListener('mousemove', function (e) {
      mx = e.clientX; my = e.clientY;
      dot.style.transform = 'translate(' + mx + 'px,' + my + 'px)';
      var t = e.target.closest('a,button,input,textarea,select,[data-mm-cursor]');
      ring.classList.toggle('is-hover', !!t);
      var label = t && t.getAttribute('data-mm-cursor');
      if (label) { ring.textContent = label; } else if (t) { ring.textContent = ''; ring.classList.add('is-link'); }
      else { ring.textContent = ''; ring.classList.remove('is-link'); }
    });
    (function frame() {
      rx = lerp(rx, mx, 0.16); ry = lerp(ry, my, 0.16);
      ring.style.transform = 'translate(' + rx.toFixed(1) + 'px,' + ry.toFixed(1) + 'px)';
      requestAnimationFrame(frame);
    })();
    html.classList.add('mm-cursor-on');
  }

  /* ================= Horizontal pinned gallery ======================== */
  function initHScroll() {
    document.querySelectorAll('[data-mm-hscroll]').forEach(function (sec) {
      var track = sec.querySelector('.mm-hs__track');
      if (!track) return;
      sec.classList.add('mm-hs');
      function measure() {
        var dist = Math.max(0, track.scrollWidth - window.innerWidth);
        sec.style.height = (dist + window.innerHeight) + 'px';
        track.style.position = 'sticky';
        track.style.top = '0';
      }
      measure();
      window.addEventListener('resize', measure);
      rafs.push(function () {
        var r = sec.getBoundingClientRect();
        var dist = Math.max(0, track.scrollWidth - window.innerWidth);
        var p = clamp(-r.top / (r.height - window.innerHeight || 1), 0, 1);
        track.style.transform = 'translate3d(' + (-p * dist).toFixed(1) + 'px,0,0)';
      });
    });
  }

  /* ================= Count-up numbers ================================== */
  function initCounters() {
    var els = document.querySelectorAll('[data-mm-count]');
    if (!els.length) return;
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target;
        io.unobserve(el);
        var to = parseFloat(el.getAttribute('data-mm-count'));
        var suffix = el.getAttribute('data-mm-suffix') || '';
        var prefix = el.getAttribute('data-mm-prefix') || '';
        var t0 = performance.now(), dur = 1400;
        (function step(t) {
          var p = clamp((t - t0) / dur, 0, 1);
          var e = 1 - Math.pow(1 - p, 3);
          el.textContent = prefix + Math.round(to * e).toLocaleString() + suffix;
          if (p < 1) requestAnimationFrame(step);
        })(t0);
      });
    }, { threshold: 0.4 });
    els.forEach(function (el) { io.observe(el); });
  }

  /* ================= Scroll progress + grain =========================== */
  function initChrome() {
    var dark = null;
    if (useProgress) {
      var bar = document.createElement('div');
      bar.className = 'mm-progress';
      bar.innerHTML = '<i></i>';
      document.body.appendChild(bar);
      var fill = bar.querySelector('i');
      rafs.push(function () {
        var max = document.body.scrollHeight - innerHeight;
        fill.style.transform = 'scaleX(' + (max > 0 ? clamp(scroll.current / max, 0, 1) : 0) + ')';
      });
    }
    if (useGrain) {
      var g = document.createElement('div');
      g.className = 'mm-grain';
      document.body.appendChild(g);
    }
    // nav hide/show on scroll direction (only for fixed bars tagged or common nav selectors)
    var nav = document.querySelector('.navbar, header.mm-autohide, [data-mm-nav]');
    if (nav && getComputedStyle(nav).position === 'fixed') {
      nav.classList.add('mm-nav');
      var last = 0;
      rafs.push(function () {
        var y = scroll.current;
        var down = y > last && y > 220;
        nav.classList.toggle('mm-nav--hidden', down);
        nav.classList.toggle('is-scrolled', y > 40);
        last = y;
      });
    }
  }

  /* ================= Preloader drive =================================== */
  function drivePreloader(done) {
    if (!pre) { done(); return; }
    var finished = false;
    function finish() {
      if (finished) return;
      finished = true;
      clearInterval(iv);
      pre.classList.add('is-done');
      html.classList.remove('mm-preloading');
      html.classList.add('mm-loaded');
      if (!location.hash) window.scrollTo(0, 0); // undo browser scroll-restoration
      setTimeout(function () { if (pre.parentNode) pre.remove(); done(); }, 900);
    }
    var num = pre.querySelector('.mm-preload__num');
    var pct = pre.querySelector('.mm-preload__pct i');
    var t0 = performance.now(), dur = opt('preloadMs', 1200);
    var loaded = document.readyState === 'complete';
    window.addEventListener('load', function () { loaded = true; });
    // interval (not rAF) so hidden tabs still progress
    var iv = setInterval(function () {
      var p = clamp((performance.now() - t0) / dur, 0, 1);
      var v = Math.round(clamp(p * 100 + (loaded ? 8 : 0), 0, 100));
      num.textContent = v;
      pct.style.transform = 'scaleX(' + (v / 100) + ')';
      if (v >= 100) finish();
    }, 50);
    // safety: never trap the page
    setTimeout(finish, 5000);
  }

  /* ================= boot ============================================== */
  function boot() {
    if (OPT.accent) html.style.setProperty('--mm-accent', OPT.accent);
    if (bodyIsDark()) html.classList.add('mm-dark'); else html.classList.add('mm-light');
    initSmoothScroll();
    initFallbackTicker();
    initSplit();
    initReveals();
    initMarquee();
    initParallax();
    initMagnetic();
    initCursor();
    initHScroll();
    initCounters();
    initChrome();
    html.classList.add('mm-ready');
    // re-measure parallax targets after images load
    window.addEventListener('load', function () {
      parallaxEls = [];
      initParallax();
      initMagnetic();
      initMarquee();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      drivePreloader(function () { boot(); });
    });
    // if no preloader, boot immediately on ready
    if (!pre) document.addEventListener('DOMContentLoaded', boot);
  } else {
    drivePreloader(function () { boot(); });
    if (!pre) boot();
  }
})();

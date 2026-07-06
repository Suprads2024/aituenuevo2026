/* =========================================================
   AITUE — Rediseño Web
   main.js  ·  interacciones (nav, hotspots, formulario, reveal, carrusel)
   ========================================================= */
(function () {
  'use strict';

  /* ---------- 1. Header: fondo sólido al hacer scroll ---------- */
  var header = document.querySelector('.header');
  function onScroll() {
    if (window.scrollY > 50) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------- 2. Menú mobile (drawer) ---------- */
  var burger = document.querySelector('.burger');
  var drawer = document.querySelector('.drawer');
  var scrim = document.querySelector('.drawer-scrim');
  var drawerClose = document.querySelector('.drawer-close');

  function openDrawer() { drawer.classList.add('open'); scrim.classList.add('open'); }
  function closeDrawer() { drawer.classList.remove('open'); scrim.classList.remove('open'); }

  if (burger) burger.addEventListener('click', openDrawer);
  if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
  if (scrim) scrim.addEventListener('click', closeDrawer);

  /* ---------- 3. Scroll suave con offset por el header fijo ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href');
      if (id.length < 2) return;
      var el = document.querySelector(id);
      if (!el) return;
      e.preventDefault();
      var top = el.getBoundingClientRect().top + window.scrollY - 78;
      window.scrollTo({ top: top, behavior: 'smooth' });
      closeDrawer();
    });
  });

  /* ---------- 4. (Las secciones de Componentes y ULTRA+ ahora son estáticas) ---------- */

  /* ---------- 5. Formulario de contacto ---------- */
  var form = document.getElementById('contact-form');
  var formDone = document.getElementById('form-done');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      // Aquí podés conectar tu backend (fetch a contacto.php, EmailJS, etc.)
      // var data = new FormData(form);
      form.classList.add('hidden');
      if (formDone) formDone.classList.remove('hidden');
    });
  }

  /* ---------- 6. Reveal on scroll ---------- */
  var reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.1 });
    reveals.forEach(function (el, i) {
      el.style.transitionDelay = (i % 5) * 0.05 + 's';
      io.observe(el);
    });
  } else {
    reveals.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---------- 7. Año dinámico del footer ---------- */
  var y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();

  /* ---------- 8. Carrusel del hero (auto + arrastre) ---------- */
  (function () {
    var track = document.getElementById('heroTrack');
    if (!track) return;
    var viewport = track.parentElement;
    var speed = 0.5;            // velocidad automática (px por frame) → derecha
    var offset = 0, setWidth = 0;
    var dragging = false, startX = 0, startOffset = 0;

    function normalize() {
      if (setWidth <= 0) return;
      while (offset <= -setWidth) offset += setWidth;
      while (offset > 0) offset -= setWidth;
    }
    function measure() { setWidth = track.scrollWidth / 2; normalize(); }

    function frame() {
      if (!dragging && setWidth > 0) { offset += speed; normalize(); }
      track.style.transform = 'translateX(' + offset + 'px)';
      requestAnimationFrame(frame);
    }

    function pointerX(e) { return e.touches ? e.touches[0].clientX : e.clientX; }
    function down(e) { dragging = true; startX = pointerX(e); startOffset = offset; }
    function move(e) { if (!dragging) return; offset = startOffset + (pointerX(e) - startX); normalize(); }
    function up() { dragging = false; }

    viewport.addEventListener('mousedown', down);
    window.addEventListener('mousemove', move);
    window.addEventListener('mouseup', up);
    viewport.addEventListener('touchstart', down, { passive: true });
    viewport.addEventListener('touchmove', move, { passive: true });
    viewport.addEventListener('touchend', up);

    window.addEventListener('load', measure);
    window.addEventListener('resize', measure);
    setTimeout(measure, 400);   // por si las imágenes tardan en cargar
    requestAnimationFrame(frame);
  })();
})();

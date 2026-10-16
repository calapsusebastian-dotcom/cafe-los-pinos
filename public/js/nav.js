// Resalta en el menú la sección visible y cierra el menú móvil al elegir un enlace.
(function () {
  var nav = document.getElementById('nav-principal');
  var toggle = document.querySelector('.nav-toggle');
  if (!nav) { return; }

  nav.addEventListener('click', function (event) {
    if (event.target.closest('.nav__link') && nav.classList.contains('nav--open')) {
      nav.classList.remove('nav--open');
      if (toggle) { toggle.setAttribute('aria-expanded', 'false'); }
    }
  });

  if (!('IntersectionObserver' in window)) { return; }

  var links = {};
  nav.querySelectorAll('.nav__link').forEach(function (link) {
    if (link.hash && link.pathname === window.location.pathname) { links[link.hash.slice(1)] = link; }
  });

  var ids = Object.keys(links);
  if (!ids.length) { return; }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) { return; }
      nav.querySelectorAll('.nav__link--active').forEach(function (active) { active.classList.remove('nav__link--active'); });
      links[entry.target.id].classList.add('nav__link--active');
    });
  }, { rootMargin: '-40% 0px -55% 0px' });

  ids.forEach(function (id) {
    var section = document.getElementById(id);
    if (section) { observer.observe(section); }
  });
})();

// Buscador desplegable del encabezado.
(function () {
  var toggle = document.querySelector('[data-search-toggle]');
  var form = document.getElementById('buscador');
  if (!toggle || !form) { return; }

  function setOpen(open) {
    form.hidden = !open;
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open) { form.querySelector('input').focus(); }
  }

  if (form.querySelector('input').value) { setOpen(true); }

  toggle.addEventListener('click', function () { setOpen(form.hidden); });
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && !form.hidden) { setOpen(false); toggle.focus(); }
  });
})();

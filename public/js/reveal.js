// Muestra con una animación los bloques [data-reveal] cuando entran en pantalla por abajo.
// Los que ya están a la vista al cargar, o que quedaron arriba tras saltar a una sección,
// aparecen al instante para que no se vuelvan a animar al volver a ellos.
(function () {
  var items = document.querySelectorAll('[data-reveal]');

  function showNow(item) {
    item.classList.add('reveal-instant', 'is-visible');
  }

  if (!('IntersectionObserver' in window)) {
    items.forEach(showNow);
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      } else if (entry.boundingClientRect.top >= 0) {
        return;
      } else {
        showNow(entry.target);
      }
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.15 });

  items.forEach(function (item) {
    var box = item.getBoundingClientRect();
    if (box.top < window.innerHeight * 0.9) {
      showNow(item);
    } else {
      observer.observe(item);
    }
  });
})();

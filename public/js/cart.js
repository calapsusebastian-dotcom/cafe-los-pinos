// Carrito de compras (guardado en el navegador). Al finalizar, el pedido se registra en el
// servidor y se abre WhatsApp con el resumen.
(function () {
  var STORAGE_KEY = 'lospinos-cart';
  var drawer = document.getElementById('carrito');
  if (!drawer) { return; }

  var overlay = document.querySelector('.cart-overlay');
  var list = drawer.querySelector('[data-cart-items]');
  var empty = drawer.querySelector('[data-cart-empty]');
  var foot = drawer.querySelector('[data-cart-foot]');
  var totalEl = drawer.querySelector('[data-cart-total]');
  var subtotalEl = drawer.querySelector('[data-cart-subtotal]');
  var shippingEl = drawer.querySelector('[data-cart-shipping]');
  var checkoutButton = drawer.querySelector('[data-cart-checkout]');
  var form = drawer.querySelector('[data-cart-form]');
  var errorEl = drawer.querySelector('[data-cart-error]');
  var submitButton = drawer.querySelector('[data-cart-submit]');
  var done = drawer.querySelector('[data-cart-done]');
  var shippingCost = Number(drawer.dataset.shippingCost) || 0;
  var freeShippingFrom = Number(drawer.dataset.freeShippingFrom) || 0;
  var counters = document.querySelectorAll('[data-cart-count]');
  var lastFocus = null;

  function load() {
    try {
      var data = JSON.parse(localStorage.getItem(STORAGE_KEY));
      return Array.isArray(data) ? data : [];
    } catch (e) {
      return [];
    }
  }

  function save(items) {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(items)); } catch (e) { /* sin almacenamiento */ }
  }

  var items = load();

  function money(value) {
    return '$ ' + Number(value).toLocaleString('es-CO');
  }

  function escapeText(value) {
    var node = document.createElement('span');
    node.textContent = value;
    return node.innerHTML.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  }

  function render() {
    var count = 0;
    var total = 0;

    list.innerHTML = items.map(function (item, index) {
      count += item.qty;
      total += item.qty * item.price;

      return '<li class="cart__item">' +
        '<img src="' + escapeText(item.image) + '" alt="" class="cart__thumb">' +
        '<div class="cart__info">' +
          '<p class="cart__name">' + escapeText(item.name) + '</p>' +
          '<p class="cart__meta">' + escapeText(item.weight) + ' · ' + money(item.price) + '</p>' +
          '<div class="cart__qty">' +
            '<button type="button" aria-label="Quitar una unidad" data-cart-dec="' + index + '">−</button>' +
            '<span aria-live="polite">' + item.qty + '</span>' +
            '<button type="button" aria-label="Agregar una unidad" data-cart-inc="' + index + '">+</button>' +
          '</div>' +
        '</div>' +
        '<button type="button" class="cart__remove" aria-label="Eliminar ' + escapeText(item.name) + '" data-cart-remove="' + index + '">×</button>' +
      '</li>';
    }).join('');

    var shipping = freeShippingFrom > 0 && total >= freeShippingFrom ? 0 : shippingCost;

    counters.forEach(function (counter) { counter.textContent = count; });
    empty.hidden = items.length > 0 || !done.hidden;
    foot.hidden = items.length === 0;
    subtotalEl.textContent = money(total);
    shippingEl.textContent = shipping > 0 ? money(shipping) : (shippingCost > 0 ? 'Gratis' : 'Por confirmar');
    totalEl.textContent = money(total + shipping);

    if (items.length === 0) { showForm(false); }
  }

  function showForm(visible) {
    form.hidden = !visible;
    checkoutButton.hidden = visible;
    errorEl.hidden = true;
  }

  function showError(message) {
    errorEl.textContent = message;
    errorEl.hidden = false;
  }

  function submitOrder(event) {
    event.preventDefault();
    if (!form.reportValidity() || items.length === 0) { return; }

    var token = document.querySelector('meta[name="csrf-token"]');
    var popup = window.open('', '_blank');
    var data = new FormData(form);
    var payload = {
      customer_name: data.get('customer_name'),
      customer_phone: data.get('customer_phone'),
      city: data.get('city'),
      address: data.get('address'),
      notes: data.get('notes'),
      items: items.map(function (item) { return { id: Number(item.id), qty: item.qty }; })
    };

    submitButton.disabled = true;
    errorEl.hidden = true;

    fetch(drawer.dataset.ordersUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token ? token.content : ''
      },
      body: JSON.stringify(payload)
    }).then(function (response) {
      return response.json().then(function (body) { return { ok: response.ok, body: body }; });
    }).then(function (result) {
      if (!result.ok) {
        var errors = result.body.errors ? Object.keys(result.body.errors).map(function (key) { return result.body.errors[key][0]; }) : [];
        throw new Error(errors.length ? errors.join(' ') : 'No pudimos registrar tu pedido. Inténtalo de nuevo.');
      }

      items = [];
      save(items);
      form.reset();
      done.hidden = false;
      drawer.querySelector('[data-cart-done-number]').textContent = '#' + result.body.order;
      drawer.querySelector('[data-cart-done-link]').href = result.body.whatsapp_url;
      render();

      if (popup) { popup.location.href = result.body.whatsapp_url; }
    }).catch(function (error) {
      if (popup) { popup.close(); }
      showError(error.message || 'No pudimos registrar tu pedido. Inténtalo de nuevo.');
    }).then(function () {
      submitButton.disabled = false;
    });
  }

  function openCart() {
    lastFocus = document.activeElement;
    drawer.classList.add('cart--open');
    drawer.setAttribute('aria-hidden', 'false');
    overlay.hidden = false;
    document.body.classList.add('no-scroll');
    drawer.querySelector('.cart__close').focus();
  }

  function closeCart() {
    drawer.classList.remove('cart--open');
    drawer.setAttribute('aria-hidden', 'true');
    overlay.hidden = true;
    document.body.classList.remove('no-scroll');
    if (lastFocus) { lastFocus.focus(); }
  }

  function add(button) {
    var id = button.dataset.id;
    done.hidden = true;
    var existing = items.filter(function (item) { return item.id === id; })[0];

    if (existing) {
      existing.qty += 1;
    } else {
      items.push({
        id: id,
        name: button.dataset.name,
        weight: button.dataset.weight,
        price: Number(button.dataset.price),
        image: button.dataset.image,
        qty: 1
      });
    }

    save(items);
    render();
  }

  document.addEventListener('click', function (event) {
    var target = event.target;
    var addButton = target.closest('[data-add-to-cart]');

    if (addButton) {
      add(addButton);
      openCart();
      return;
    }

    if (target.closest('[data-cart-checkout]')) {
      showForm(true);
      form.querySelector('input').focus();
      return;
    }

    if (target.closest('[data-cart-open]')) { openCart(); return; }
    if (target.closest('[data-cart-close]')) { closeCart(); return; }

    if (target.closest('[data-cart-clear]')) {
      items = [];
      save(items);
      render();
      return;
    }

    var inc = target.closest('[data-cart-inc]');
    var dec = target.closest('[data-cart-dec]');
    var remove = target.closest('[data-cart-remove]');

    if (inc) { items[Number(inc.dataset.cartInc)].qty += 1; }
    if (dec) {
      var line = items[Number(dec.dataset.cartDec)];
      line.qty -= 1;
      if (line.qty < 1) { items.splice(Number(dec.dataset.cartDec), 1); }
    }
    if (remove) { items.splice(Number(remove.dataset.cartRemove), 1); }

    if (inc || dec || remove) {
      save(items);
      render();
    }
  });

  form.addEventListener('submit', submitOrder);

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && drawer.classList.contains('cart--open')) { closeCart(); }
  });

  render();
})();

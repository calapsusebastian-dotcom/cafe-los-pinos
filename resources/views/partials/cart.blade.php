  <div class="cart-overlay" data-cart-close hidden></div>
  <aside class="cart" id="carrito" aria-label="Carrito de compras" aria-hidden="true"
         data-whatsapp="{{ 'https://wa.me/'.preg_replace('/\D/', '', $content['whatsapp_number']) }}"
         data-orders-url="{{ route('orders.store') }}"
         data-shipping-cost="{{ (int) $content['shipping_cost'] }}"
         data-free-shipping-from="{{ (int) $content['free_shipping_from'] }}">
    <div class="cart__head">
      <h2 class="cart__title">Tu carrito</h2>
      <button type="button" class="icon-btn cart__close" aria-label="Cerrar carrito" data-cart-close>
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>
    </div>

    <ul class="cart__items" data-cart-items></ul>

    <p class="cart__empty" data-cart-empty>Tu carrito está vacío. Agrega tu café favorito y envíanos el pedido por WhatsApp.</p>

    <div class="cart__foot" data-cart-foot hidden>
      <dl class="cart__totals">
        <div><dt>Subtotal</dt><dd data-cart-subtotal>$ 0</dd></div>
        <div><dt>Envío</dt><dd data-cart-shipping>Por confirmar</dd></div>
        <div class="cart__total"><dt>Total</dt><dd><strong data-cart-total>$ 0</strong></dd></div>
      </dl>

      <button type="button" class="btn cart__order" data-cart-checkout>Continuar con mi pedido</button>

      <form class="checkout" data-cart-form novalidate hidden>
        <p class="checkout__title">¿A dónde te lo enviamos?</p>

        <label for="checkout-name">Nombre completo</label>
        <input type="text" id="checkout-name" name="customer_name" required maxlength="100" autocomplete="name">

        <label for="checkout-phone">Celular</label>
        <input type="tel" id="checkout-phone" name="customer_phone" required maxlength="20" inputmode="numeric" autocomplete="tel" placeholder="3001234567">

        <label for="checkout-city">Ciudad</label>
        <input type="text" id="checkout-city" name="city" required maxlength="80" autocomplete="address-level2">

        <label for="checkout-address">Dirección de entrega</label>
        <input type="text" id="checkout-address" name="address" required maxlength="200" autocomplete="street-address">

        <label for="checkout-notes">Notas (opcional)</label>
        <textarea id="checkout-notes" name="notes" rows="2" maxlength="300" placeholder="Molienda, indicaciones de entrega…"></textarea>

        <p class="checkout__error" role="alert" data-cart-error hidden></p>

        <button type="submit" class="btn cart__order" data-cart-submit>
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.39-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.21 3.07.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35zM12.05 21.79h-.01a9.87 9.87 0 01-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 01-1.51-5.26c0-5.45 4.44-9.88 9.89-9.88 2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 012.89 6.99c0 5.45-4.44 9.88-9.88 9.88zm8.41-18.3A11.82 11.82 0 0012.05 0C5.5 0 .16 5.34.16 11.89c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.88 11.88 0 005.69 1.45h.01c6.55 0 11.89-5.34 11.89-11.89 0-3.18-1.24-6.17-3.48-8.41z"/></svg>
          Enviar pedido por WhatsApp
        </button>
      </form>

      <button type="button" class="cart__clear" data-cart-clear>Vaciar carrito</button>
    </div>

    <div class="cart__done" data-cart-done hidden>
      <p class="cart__done-title">¡Pedido <span data-cart-done-number></span> registrado!</p>
      <p>Para terminar, envíanos el mensaje por WhatsApp y confirmamos tu pedido.</p>
      <a href="#" class="btn cart__order" target="_blank" rel="noopener" data-cart-done-link>Abrir WhatsApp</a>
    </div>
  </aside>

@if ($product->is_sold_out)
  <button type="button" class="btn btn--dark btn--small" disabled>Agotado</button>
@else
  <button type="button" class="btn btn--dark btn--small" data-add-to-cart
          data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-weight="{{ $product->weight }}"
          data-price="{{ $product->price }}" data-image="{{ $product->image_url }}">
    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.5 11h10L20 7H6.5"/><circle cx="9" cy="19.5" r="1.2"/><circle cx="17" cy="19.5" r="1.2"/></svg>
    Agregar al carrito
  </button>
@endif

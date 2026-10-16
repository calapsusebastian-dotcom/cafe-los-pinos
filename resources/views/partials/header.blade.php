  <header class="header">
    <a href="{{ route('home') }}" class="header__logo" aria-label="Café Los Pinos - Inicio">
      <img src="{{ asset('img/logo-header.png') }}" alt="Café Los Pinos - Tradición que se disfruta">
    </a>

    <button class="nav-toggle" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-principal"
            onclick="const open = this.nextElementSibling.classList.toggle('nav--open'); this.setAttribute('aria-expanded', open);">
      <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>

    <nav class="nav" id="nav-principal" aria-label="Navegación principal">
      <ul class="nav__list">
        <li><a href="{{ route('home') }}#inicio" class="nav__link {{ request()->routeIs('home') ? 'nav__link--active' : '' }}">Inicio</a></li>
        <li><a href="{{ route('story') }}" class="nav__link {{ request()->routeIs('story') ? 'nav__link--active' : '' }}">Nuestra Historia</a></li>
        <li><a href="{{ route('home') }}#productos" class="nav__link">Productos</a></li>
        <li><a href="{{ route('blog.index') }}" class="nav__link {{ request()->routeIs('blog.*') ? 'nav__link--active' : '' }}">Blog</a></li>
        <li><a href="{{ route('home') }}#tienda" class="nav__link">Tienda</a></li>
        <li><a href="#contacto" class="nav__link">Contacto</a></li>
      </ul>
    </nav>

    <div class="header__actions">
      <button class="icon-btn" type="button" aria-label="Buscar productos" aria-expanded="false" aria-controls="buscador" data-search-toggle>
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
      </button>
      <button class="icon-btn icon-btn--cart" type="button" aria-label="Abrir carrito" aria-controls="carrito" data-cart-open>
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.5 11h10L20 7H6.5"/><circle cx="9" cy="19.5" r="1.2"/><circle cx="17" cy="19.5" r="1.2"/></svg>
        <span class="cart-count" data-cart-count>0</span>
      </button>
    </div>

    <form class="search" id="buscador" action="{{ route('home') }}#productos" method="GET" role="search" hidden>
      <label for="q" class="sr-only">Buscar productos</label>
      <input type="search" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Busca tu café: clásico, premium..." maxlength="80" autocomplete="off">
      <button type="submit" class="btn btn--small">Buscar</button>
    </form>
  </header>

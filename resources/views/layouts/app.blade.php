<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Café Los Pinos · Café del Huila tostado con pasión')</title>
  <meta name="description" content="@yield('description', $content['seo_description'])">
  <link rel="canonical" href="@yield('canonical', url()->current())">
  <link rel="alternate" hreflang="es-CO" href="@yield('canonical', url()->current())">
  <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#12261f">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:site_name" content="Café Los Pinos">
  <meta property="og:locale" content="es_CO">
  <meta property="og:title" content="@yield('title', 'Café Los Pinos · Café del Huila tostado con pasión')">
  <meta property="og:description" content="@yield('description', $content['seo_description'])">
  <meta property="og:url" content="@yield('canonical', url()->current())">
  <meta property="og:image" content="@yield('og_image', asset('img/finca.png'))">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('title', 'Café Los Pinos · Café del Huila tostado con pasión')">
  <meta name="twitter:description" content="@yield('description', $content['seo_description'])">
  <meta name="twitter:image" content="@yield('og_image', asset('img/finca.png'))">
  <link rel="icon" type="image/png" href="{{ asset('img/logo-header.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Source+Serif+4:wght@500;600&family=Dancing+Script:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <script>
    document.documentElement.classList.add('js');
    try {
      if (sessionStorage.getItem('lospinos-intro')) { document.documentElement.classList.add('no-intro'); }
      else { sessionStorage.setItem('lospinos-intro', '1'); }
    } catch (e) { /* sin almacenamiento: la animación de entrada se repite */ }
  </script>
  <script src="{{ asset('js/reveal.js') }}" defer></script>
  <script src="{{ asset('js/nav.js') }}" defer></script>
  <script src="{{ asset('js/cart.js') }}" defer></script>
  @stack('head')
</head>
<body>

  <a href="#contenido" class="skip-link">Saltar al contenido</a>

  @include('partials.header')

  <main id="contenido">
    @yield('content')
  </main>

  @include('partials.footer')

  @include('partials.cart')

  @include('partials.whatsapp')

</body>
</html>

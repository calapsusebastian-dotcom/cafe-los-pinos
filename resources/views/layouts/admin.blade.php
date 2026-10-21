<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('title', 'Administrador') · Café Los Pinos</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo-header.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Source+Serif+4:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body class="admin">

  <header class="admin-bar">
    <a href="{{ route('admin.products.index') }}" class="admin-bar__brand">
      <img src="{{ asset('img/logo-header.png') }}" alt="Café Los Pinos">
      <span>Administrador</span>
    </a>

    <nav class="admin-bar__nav" aria-label="Administrador">
      <a href="{{ route('admin.orders.index') }}" @class(['is-active' => request()->routeIs('admin.orders.*')])>Pedidos</a>
      <a href="{{ route('admin.products.index') }}" @class(['is-active' => request()->routeIs('admin.products.*')])>Productos</a>
      <a href="{{ route('admin.posts.index') }}" @class(['is-active' => request()->routeIs('admin.posts.*')])>Blog</a>
      <a href="{{ route('admin.subscribers.index') }}" @class(['is-active' => request()->routeIs('admin.subscribers.*')])>Suscriptores</a>
      <a href="{{ route('admin.content.edit') }}" @class(['is-active' => request()->routeIs('admin.content.*')])>Textos del sitio</a>
      <a href="{{ route('home') }}" target="_blank" rel="noopener">Ver sitio ↗</a>
    </nav>

    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="admin-bar__logout">Cerrar sesión</button>
    </form>
  </header>

  <main class="admin-main">
    @if (session('status'))
      <div class="alert alert--success" role="status">{{ session('status') }}</div>
    @endif

    @yield('content')
  </main>

</body>
</html>

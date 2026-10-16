@extends('layouts.app')

@section('title', $post->title.' · Café Los Pinos')
@section('description', $post->excerpt)
@section('canonical', route('blog.show', $post))
@section('og_type', 'article')

@push('head')
  <meta property="article:published_time" content="{{ $post->published_at->toAtomString() }}">
  <meta property="article:modified_time" content="{{ $post->updated_at->toAtomString() }}">
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'url' => route('blog.show', $post),
        'mainEntityOfPage' => route('blog.show', $post),
        'image' => asset('img/finca.png'),
        'inLanguage' => 'es-CO',
        'datePublished' => $post->published_at->toAtomString(),
        'dateModified' => $post->updated_at->toAtomString(),
        'author' => ['@type' => 'Organization', 'name' => 'Café Los Pinos'],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Café Los Pinos',
            'logo' => ['@type' => 'ImageObject', 'url' => asset('img/logo-header.png')],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('blog.show', $post)],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
@endpush

@section('content')
    <article class="blog blog--post">
      <nav class="breadcrumb" aria-label="Ruta">
        <a href="{{ route('home') }}">Inicio</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('blog.index') }}">Blog</a>
        <span aria-hidden="true">/</span>
        <span>{{ $post->title }}</span>
      </nav>

      <header class="blog__head">
        <time class="post-card__date" datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale('es')->translatedFormat('j \d\e F, Y') }}</time>
        <h1 class="blog__title">{{ $post->title }}</h1>
        <p class="blog__lead">{{ $post->excerpt }}</p>
      </header>

      <div class="prose">
        {{ $post->body_html }}
      </div>

      <aside class="blog__cta">
        <p>¿Quieres probar el café del Huila que hacemos con tanto cariño?</p>
        <a href="{{ route('home') }}#productos" class="btn btn--dark">Ver nuestros cafés <span aria-hidden="true">→</span></a>
      </aside>
    </article>

    @if ($more->isNotEmpty())
      <section class="related">
        <h2 class="related__title">Sigue leyendo</h2>
        <div class="blog__grid">
          @foreach ($more as $item)
            <article class="post-card">
              <time class="post-card__date" datetime="{{ $item->published_at->toDateString() }}">{{ $item->published_at->locale('es')->translatedFormat('j \d\e F, Y') }}</time>
              <h3 class="post-card__title"><a href="{{ route('blog.show', $item) }}">{{ $item->title }}</a></h3>
              <p class="post-card__excerpt">{{ $item->excerpt }}</p>
            </article>
          @endforeach
        </div>
      </section>
    @endif
@endsection

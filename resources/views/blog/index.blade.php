@extends('layouts.app')

@section('title', 'Blog del café · Guías y notas de cata · Café Los Pinos')
@section('description', 'Guías para preparar mejor tu café, notas de cata y historias del Huila. Aprende con Café Los Pinos.')
@section('canonical', $posts->url($posts->currentPage()))

@section('content')
    <section class="blog">
      <div class="blog__head">
        <p class="eyebrow">Blog</p>
        <h1 class="blog__title">Historias y guías del café del Huila</h1>
        <p class="blog__lead">Aprende a prepararlo, a catarlo y a conocer de dónde viene cada taza.</p>
      </div>

      <div class="blog__grid">
        @forelse ($posts as $post)
          <article class="post-card">
            <time class="post-card__date" datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale('es')->translatedFormat('j \d\e F, Y') }}</time>
            <h2 class="post-card__title"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h2>
            <p class="post-card__excerpt">{{ $post->excerpt }}</p>
            <a href="{{ route('blog.show', $post) }}" class="post-card__more" aria-label="Leer {{ $post->title }}">Leer artículo <span aria-hidden="true">→</span></a>
          </article>
        @empty
          <p class="products__empty">Pronto publicaremos nuestros primeros artículos.</p>
        @endforelse
      </div>

      @if ($posts->hasPages())
        <nav class="blog__pager" aria-label="Paginación">
          @if ($posts->previousPageUrl())
            <a href="{{ $posts->previousPageUrl() }}" rel="prev">← Más recientes</a>
          @endif
          @if ($posts->nextPageUrl())
            <a href="{{ $posts->nextPageUrl() }}" rel="next">Anteriores →</a>
          @endif
        </nav>
      @endif
    </section>
@endsection

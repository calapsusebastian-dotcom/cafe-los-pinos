@extends('layouts.app')

@section('title', 'Nuestra Historia · Café Los Pinos')
@section('description', 'Conoce la historia de Café Los Pinos: caficultores del Huila, café de origen y tostión con pasión.')

@section('canonical', route('story'))

@push('head')
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'name' => 'Nuestra Historia · Café Los Pinos',
        'url' => route('story'),
        'inLanguage' => 'es-CO',
        'about' => ['@type' => 'Organization', 'name' => 'Café Los Pinos', 'url' => route('home')],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
@endpush

@section('content')
    <section class="history-hero" style="--section-image: url('{{ $images['story_hero_image'] }}')">
      <div class="history-hero__content">
        <p class="hero__eyebrow">Nuestra historia</p>
        <h1 class="hero__title">{!! nl2br(e($content['story_quote'])) !!}</h1>
        <p class="hero__text">{{ $content['story_text'] }}</p>
      </div>
      <a href="#origen" class="history-hero__scroll" aria-label="Seguir leyendo">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
      </a>
    </section>

    <div class="chapters-wrap" id="origen">
    <section class="chapters">
      <article class="chapter" data-reveal>
        <div class="chapter__media chapter__media--1 @if (! str_ends_with($images['chapter_1_image'], 'img/finca.png')) chapter__media--custom @endif" style="--section-image: url('{{ $images['chapter_1_image'] }}')" aria-hidden="true"><span class="chapter__number">01</span></div>
        <div class="chapter__body">
          <p class="eyebrow">{{ $content['chapter_1_eyebrow'] }}</p>
          <h2 class="chapter__title">{{ $content['chapter_1_title'] }}</h2>
          <p class="chapter__text">{{ $content['chapter_1_text'] }}</p>
        </div>
      </article>

      <article class="chapter chapter--reverse" data-reveal>
        <div class="chapter__media chapter__media--2 @if (! str_ends_with($images['chapter_2_image'], 'img/finca.png')) chapter__media--custom @endif" style="--section-image: url('{{ $images['chapter_2_image'] }}')" aria-hidden="true"><span class="chapter__number">02</span></div>
        <div class="chapter__body">
          <p class="eyebrow">{{ $content['chapter_2_eyebrow'] }}</p>
          <h2 class="chapter__title">{{ $content['chapter_2_title'] }}</h2>
          <p class="chapter__text">{{ $content['chapter_2_text'] }}</p>
        </div>
      </article>

      <article class="chapter" data-reveal>
        <div class="chapter__media chapter__media--3 @if (! str_ends_with($images['chapter_3_image'], 'img/finca.png')) chapter__media--custom @endif" style="--section-image: url('{{ $images['chapter_3_image'] }}')" aria-hidden="true"><span class="chapter__number">03</span></div>
        <div class="chapter__body">
          <p class="eyebrow">{{ $content['chapter_3_eyebrow'] }}</p>
          <h2 class="chapter__title">{{ $content['chapter_3_title'] }}</h2>
          <p class="chapter__text">{{ $content['chapter_3_text'] }}</p>
        </div>
      </article>

      <article class="chapter chapter--reverse" data-reveal>
        <div class="chapter__media chapter__media--4 @if (! str_ends_with($images['chapter_4_image'], 'img/finca.png')) chapter__media--custom @endif" style="--section-image: url('{{ $images['chapter_4_image'] }}')" aria-hidden="true"><span class="chapter__number">04</span></div>
        <div class="chapter__body">
          <p class="eyebrow">{{ $content['chapter_4_eyebrow'] }}</p>
          <h2 class="chapter__title">{{ $content['chapter_4_title'] }}</h2>
          <p class="chapter__text">{{ $content['chapter_4_text'] }}</p>
        </div>
      </article>
    </section>
    </div>

    <section class="values" aria-label="Nuestros valores">
      <div class="value" data-reveal style="--reveal-delay: 0s">
        <span class="value__icon">
          <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19c0-8 5-13 14-14 0 9-5 14-14 14z"/><path d="M5 19c3-4 6-7 10-9"/></svg>
        </span>
        <h3 class="value__title">{{ $content['value_1_title'] }}</h3>
        <p class="value__text">{{ $content['value_1_text'] }}</p>
      </div>
      <div class="value" data-reveal style="--reveal-delay: 0.2s">
        <span class="value__icon">
          <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-8-5-8-11a4.5 4.5 0 018-2.5A4.5 4.5 0 0120 9c0 6-8 11-8 11z"/></svg>
        </span>
        <h3 class="value__title">{{ $content['value_2_title'] }}</h3>
        <p class="value__text">{{ $content['value_2_text'] }}</p>
      </div>
      <div class="value" data-reveal style="--reveal-delay: 0.4s">
        <span class="value__icon">
          <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>
        </span>
        <h3 class="value__title">{{ $content['value_3_title'] }}</h3>
        <p class="value__text">{{ $content['value_3_text'] }}</p>
      </div>
    </section>

    <section class="history-cta" data-reveal>
      <p class="history-cta__quote">{{ $content['story_cta'] }}</p>
      <div class="history-cta__actions">
        <a href="{{ route('home') }}#productos" class="btn">Ver nuestros productos <span aria-hidden="true">→</span></a>
        <a href="{{ App\Support\SiteContent::whatsappLink($content) }}" target="_blank" rel="noopener" class="btn btn--outline">Escríbenos</a>
      </div>
    </section>
@endsection

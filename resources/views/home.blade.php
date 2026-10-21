@extends('layouts.app')

@if ($search !== '')
  @section('robots', 'noindex, follow')
  @section('canonical', route('home'))
@endif

@push('head')
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Store',
        'name' => 'Café Los Pinos',
        'description' => $content['seo_description'],
        'url' => route('home'),
        'image' => asset('img/finca.png'),
        'logo' => asset('img/logo-header.png'),
        'telephone' => '+'.preg_replace('/\D/', '', $content['whatsapp_number']),
        'areaServed' => 'CO',
        'priceRange' => '$$',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Café Los Pinos',
        'url' => route('home'),
        'inLanguage' => 'es-CO',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
@endpush

@section('content')
    <section class="hero" id="inicio" style="--section-image: url('{{ $images['hero_image'] }}')">
      <div class="hero__content">
        <p class="hero__eyebrow">{{ $content['hero_eyebrow'] }}</p>
        <h1 class="hero__title">{{ $content['hero_title'] }}</h1>
        <p class="hero__text">{{ $content['hero_text'] }}</p>
        <a href="{{ App\Support\SiteContent::whatsappLink($content) }}" target="_blank" rel="noopener" class="btn">{{ $content['hero_button'] }} <span aria-hidden="true">→</span></a>

        <ul class="badges">
          <li class="badge">
            <span class="badge__icon">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19c0-8 5-13 14-14 0 9-5 14-14 14z"/><path d="M5 19c3-4 6-7 10-9"/></svg>
            </span>
            <span>{!! nl2br(e($content['badge_1'])) !!}</span>
          </li>
          <li class="badge">
            <span class="badge__icon">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 19l6-10 4 6 2-3 6 7z"/></svg>
            </span>
            <span>{!! nl2br(e($content['badge_2'])) !!}</span>
          </li>
          <li class="badge">
            <span class="badge__icon">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>
            </span>
            <span>{!! nl2br(e($content['badge_3'])) !!}</span>
          </li>
        </ul>
      </div>
    </section>

    <section class="products" id="productos">
      <div class="products__intro" data-reveal="left">
        <p class="eyebrow">{{ $content['products_eyebrow'] }}</p>
        <h2 class="products__title">{{ $content['products_title'] }}</h2>
        <p class="products__text">{{ $content['products_text'] }}</p>
        @if ($search !== '')
          <p class="products__filter">Resultados para «{{ $search }}» · <a href="{{ route('home') }}#productos">Quitar filtro</a></p>
        @endif
        <a href="#tienda" class="btn btn--dark">{{ $content['products_button'] }} <span aria-hidden="true">→</span></a>
      </div>

      <div class="products__grid">
        @forelse ($products as $product)
          <article class="card" data-reveal style="--reveal-delay: {{ min($loop->index, 3) * 0.2 }}s">
            <a href="{{ route('product', $product) }}" class="card__link" aria-label="Ver {{ $product->name }}">
              <div class="card__img"><img src="{{ $product->image_url }}" alt="Café {{ $product->name }} Los Pinos, {{ $product->weight }}" loading="lazy"></div>
              <h3 class="card__name">{{ $product->name }}</h3>
            </a>
            <p class="card__desc">{{ $product->description }}</p>
            <p class="card__price">$ {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="card__weight">{{ $product->weight }}</p>
            <button type="button" class="btn btn--dark btn--small" data-add-to-cart
                    data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-weight="{{ $product->weight }}"
                    data-price="{{ $product->price }}" data-image="{{ $product->image_url }}">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.5 11h10L20 7H6.5"/><circle cx="9" cy="19.5" r="1.2"/><circle cx="17" cy="19.5" r="1.2"/></svg>
              Agregar al carrito
            </button>
          </article>
        @empty
          @if ($search !== '')
            <p class="products__empty">No encontramos productos para «{{ $search }}». <a href="{{ route('home') }}#productos">Ver todos los productos</a></p>
          @else
            <p class="products__empty">Pronto tendremos nuevos productos para ti.</p>
          @endif
        @endforelse
      </div>
    </section>

    <section class="story" id="historia">
      <p class="story__quote" data-reveal="left">{!! nl2br(e($content['story_quote'])) !!}</p>
      <div class="story__info" data-reveal style="--reveal-delay: 0.3s">
        <span class="story__icon">
          <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12c0-4 2-6 5-7 0 4-2 6-5 7z"/><path d="M12 12c0-4-2-6-5-7 0 4 2 6 5 7z"/><path d="M4 14h16c0 4-3 6-8 6s-8-2-8-6z"/></svg>
        </span>
        <div>
          <h2 class="story__title">{{ $content['story_title'] }}</h2>
          <p class="story__text">{{ $content['story_text'] }}</p>
        </div>
      </div>
      <a href="{{ route('story') }}" class="btn btn--outline">{{ $content['story_button'] }} <span aria-hidden="true">→</span></a>
    </section>

    <section class="features" id="tienda" aria-label="Beneficios">
      <div class="feature" data-reveal style="--reveal-delay: 0s">
        <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6h12v10H2zM14 9h4l3 3v4h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/></svg>
        <p><strong>{{ $content['feature_1_title'] }}</strong><span>{{ $content['feature_1_text'] }}</span></p>
      </div>
      <div class="feature" data-reveal style="--reveal-delay: 0.2s">
        <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/><circle cx="12" cy="16" r="1"/></svg>
        <p><strong>{{ $content['feature_2_title'] }}</strong><span>{{ $content['feature_2_text'] }}</span></p>
      </div>
      <div class="feature" data-reveal style="--reveal-delay: 0.4s">
        <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19c0-8 5-13 14-14 0 9-5 14-14 14z"/><path d="M5 19c3-4 6-7 10-9"/></svg>
        <p><strong>{{ $content['feature_3_title'] }}</strong><span>{{ $content['feature_3_text'] }}</span></p>
      </div>
      <div class="feature" data-reveal style="--reveal-delay: 0.6s">
        <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-8-5-8-11a4.5 4.5 0 018-2.5A4.5 4.5 0 0120 9c0 6-8 11-8 11z"/></svg>
        <p><strong>{{ $content['feature_4_title'] }}</strong><span>{{ $content['feature_4_text'] }}</span></p>
      </div>
    </section>
@endsection

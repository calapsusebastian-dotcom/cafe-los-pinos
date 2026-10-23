@extends('layouts.app')

@section('title', $product->name.' · Café Los Pinos')
@section('description', \Illuminate\Support\Str::limit($product->description, 150))
@section('canonical', route('product', $product))
@section('og_type', 'product')
@section('og_image', $product->image_url)

@push('head')
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => 'Café '.$product->name,
        'description' => $product->description,
        'image' => $product->image_url,
        'sku' => 'CLP-'.$product->id,
        'category' => 'Café',
        'brand' => ['@type' => 'Brand', 'name' => 'Café Los Pinos'],
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'COP',
            'price' => $product->price,
            'priceValidUntil' => now()->addYear()->toDateString(),
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => 'https://schema.org/InStock',
            'url' => route('product', $product),
            'seller' => ['@type' => 'Organization', 'name' => 'Café Los Pinos'],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
  <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Productos', 'item' => route('home').'#productos'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => route('product', $product)],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>
@endpush

@section('content')
    <section class="product-page">
      <nav class="breadcrumb" aria-label="Ruta">
        <a href="{{ route('home') }}">Inicio</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('home') }}#productos">Productos</a>
        <span aria-hidden="true">/</span>
        <span>{{ $product->name }}</span>
      </nav>

      <div class="product-page__grid">
        <div class="product-page__img" data-reveal>
          <img src="{{ $product->image_url }}" alt="Café {{ $product->name }} Los Pinos, {{ $product->weight }}" fetchpriority="high">
        </div>

        <div class="product-page__info" data-reveal style="--reveal-delay: 0.2s">
          <p class="eyebrow">Café Los Pinos</p>
          <h1 class="product-page__title">{{ $product->name }}</h1>
          <p class="product-page__weight">{{ $product->weight }}</p>
          <p class="product-page__price">$ {{ number_format($product->price, 0, ',', '.') }}</p>
          <p class="product-page__desc">{{ $product->description }}</p>

          <div class="product-page__actions">
            @include('partials.add-to-cart', ['product' => $product])
            <a href="{{ App\Support\SiteContent::whatsappLink($content, 'Hola, quiero información sobre el café '.$product->name.' ('.$product->weight.').') }}" target="_blank" rel="noopener" class="btn btn--outline btn--small">Preguntar por WhatsApp</a>
          </div>

          <ul class="product-page__perks">
            <li>{{ $content['feature_1_title'] }} · {{ $content['feature_1_text'] }}</li>
            <li>{{ $content['feature_2_title'] }} · {{ $content['feature_2_text'] }}</li>
          </ul>
        </div>
      </div>
    </section>

    @if ($related->isNotEmpty())
      <section class="related">
        <h2 class="related__title">También te puede gustar</h2>
        <div class="products__grid">
          @foreach ($related as $product)
            <article class="card">
              <a href="{{ route('product', $product) }}" class="card__link" aria-label="Ver {{ $product->name }}">
                <div class="card__img"><img src="{{ $product->image_url }}" alt="Café {{ $product->name }} Los Pinos, {{ $product->weight }}" loading="lazy"></div>
                <h3 class="card__name">{{ $product->name }}</h3>
              </a>
              <p class="card__price">$ {{ number_format($product->price, 0, ',', '.') }}</p>
              <p class="card__weight">{{ $product->weight }}</p>
              @include('partials.add-to-cart', ['product' => $product])
            </article>
          @endforeach
        </div>
      </section>
    @endif
@endsection

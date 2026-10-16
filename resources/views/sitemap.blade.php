{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ route('home') }}</loc>@if ($lastModified)<lastmod>{{ $lastModified->toAtomString() }}</lastmod>@endif<priority>1.0</priority></url>
  <url><loc>{{ route('story') }}</loc><priority>0.6</priority></url>
  <url><loc>{{ route('blog.index') }}</loc>@if ($posts->isNotEmpty())<lastmod>{{ $posts->max('updated_at')->toAtomString() }}</lastmod>@endif<priority>0.7</priority></url>
@foreach ($posts as $post)
  <url><loc>{{ route('blog.show', $post) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod><priority>0.7</priority></url>
@endforeach
@foreach ($products as $product)
  <url><loc>{{ route('product', $product) }}</loc><lastmod>{{ $product->updated_at->toAtomString() }}</lastmod><priority>0.8</priority></url>
@endforeach
</urlset>

@extends('layouts.admin')

@section('title', 'Blog')

@section('content')
  <div class="admin-head">
    <h1>Blog</h1>
    <a href="{{ route('admin.posts.create') }}" class="btn-admin">+ Nuevo artículo</a>
  </div>

  <div class="panel">
    <table class="table">
      <thead>
        <tr>
          <th>Artículo</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($posts as $post)
          <tr>
            <td>
              <strong>{{ $post->title }}</strong>
              <span class="muted">/blog/{{ $post->slug }}</span>
            </td>
            <td>{{ $post->published_at?->format('d/m/Y H:i') ?? '—' }}</td>
            <td>
              @if ($post->isLive())
                <span class="pill pill--on">Publicado</span>
              @elseif ($post->is_published)
                <span class="pill">Programado</span>
              @else
                <span class="pill">Borrador</span>
              @endif
            </td>
            <td class="table__actions">
              @if ($post->isLive())
                <a href="{{ route('blog.show', $post) }}" target="_blank" rel="noopener" class="link">Ver</a>
              @endif
              <a href="{{ route('admin.posts.edit', $post) }}" class="link">Editar</a>
              <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('¿Eliminar {{ e($post->title) }}? No se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="link link--danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="table__empty">Aún no hay artículos. Crea el primero.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

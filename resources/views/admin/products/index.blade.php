@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
  <div class="admin-head">
    <h1>Productos</h1>
    <a href="{{ route('admin.products.create') }}" class="btn-admin">+ Nuevo producto</a>
  </div>

  <div class="panel">
    <table class="table">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Producto</th>
          <th>Precio</th>
          <th>Orden</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($products as $product)
          <tr>
            <td><img src="{{ $product->image_url }}" alt="" class="table__thumb"></td>
            <td>
              <strong>{{ $product->name }}</strong>
              <span class="muted">{{ $product->weight }}</span>
            </td>
            <td>$ {{ number_format($product->price, 0, ',', '.') }}</td>
            <td>{{ $product->sort_order }}</td>
            <td>
              @if ($product->is_visible)
                <span class="pill pill--on">Visible</span>
              @else
                <span class="pill">Oculto</span>
              @endif
              @if ($product->is_sold_out)
                <span class="pill">Agotado</span>
              @endif
            </td>
            <td class="table__actions">
              <a href="{{ route('admin.products.edit', $product) }}" class="link">Editar</a>
              <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar {{ e($product->name) }}? No se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="link link--danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="table__empty">Aún no hay productos. Crea el primero.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

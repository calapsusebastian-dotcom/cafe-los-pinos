@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')
  <div class="admin-head">
    <h1>Pedidos</h1>
    <a href="{{ route('admin.orders.export', array_filter(['estado' => $status?->value])) }}" class="link">Exportar a Excel (CSV)</a>
  </div>

  <nav class="filters" aria-label="Filtrar por estado">
    <a href="{{ route('admin.orders.index') }}" @class(['pill', 'pill--on' => ! $status])>Todos</a>
    @foreach ($statuses as $option)
      <a href="{{ route('admin.orders.index', ['estado' => $option->value]) }}" @class(['pill', 'pill--on' => $status === $option])>{{ $option->label() }}</a>
    @endforeach
  </nav>

  <div class="panel">
    <table class="table">
      <thead>
        <tr>
          <th>Pedido</th>
          <th>Cliente</th>
          <th>Ciudad</th>
          <th>Total</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($orders as $order)
          <tr>
            <td>
              <strong>#{{ $order->id }}</strong>
              <span class="muted">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </td>
            <td>
              {{ $order->customer_name }}
              <span class="muted">{{ $order->customer_phone }}</span>
            </td>
            <td>{{ $order->city }}</td>
            <td>$ {{ number_format($order->total, 0, ',', '.') }}</td>
            <td><span @class(['pill', 'pill--on' => $order->status === \App\Enums\OrderStatus::Pending])>{{ $order->status->label() }}</span></td>
            <td class="table__actions"><a href="{{ route('admin.orders.show', $order) }}" class="link">Ver</a></td>
          </tr>
        @empty
          <tr><td colspan="6" class="table__empty">Aún no hay pedidos.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($orders->hasPages())
    <nav class="pager" aria-label="Paginación">
      @if ($orders->previousPageUrl())<a href="{{ $orders->previousPageUrl() }}" class="link">← Más recientes</a>@endif
      @if ($orders->nextPageUrl())<a href="{{ $orders->nextPageUrl() }}" class="link">Anteriores →</a>@endif
    </nav>
  @endif
@endsection

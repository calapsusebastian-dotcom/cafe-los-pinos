@extends('layouts.admin')

@section('title', 'Inicio')

@section('content')
  <div class="admin-head"><h1>Inicio</h1></div>

  <div class="stats">
    <a href="{{ route('admin.orders.index', ['estado' => 'pending']) }}" class="stat">
      <span class="stat__value">{{ $pendingOrders }}</span>
      <span class="stat__label">Pedidos pendientes</span>
    </a>
    <div class="stat">
      <span class="stat__value">{{ $monthOrders }}</span>
      <span class="stat__label">Pedidos este mes</span>
    </div>
    <div class="stat">
      <span class="stat__value">$ {{ number_format($monthSales, 0, ',', '.') }}</span>
      <span class="stat__label">Ventas este mes</span>
    </div>
    <a href="{{ route('admin.subscribers.index') }}" class="stat">
      <span class="stat__value">{{ $subscribers }}</span>
      <span class="stat__label">Suscriptores</span>
    </a>
  </div>

  <div class="panel">
    <h2 class="panel__title">Más vendidos este mes</h2>
    <table class="table">
      <tbody>
        @forelse ($topProducts as $product)
          <tr>
            <td>Café {{ $product->name }} <span class="muted">{{ $product->weight }}</span></td>
            <td>{{ $product->units }} {{ $product->units == 1 ? 'unidad' : 'unidades' }}</td>
          </tr>
        @empty
          <tr><td class="table__empty">Aún no hay ventas este mes.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="panel">
    <h2 class="panel__title">Últimos pedidos</h2>
    <table class="table">
      <tbody>
        @forelse ($recentOrders as $order)
          <tr>
            <td><strong>#{{ $order->id }}</strong> <span class="muted">{{ $order->created_at->format('d/m/Y H:i') }}</span></td>
            <td>{{ $order->customer_name }}</td>
            <td>$ {{ number_format($order->total, 0, ',', '.') }}</td>
            <td><span @class(['pill', 'pill--on' => $order->status === \App\Enums\OrderStatus::Pending])>{{ $order->status->label() }}</span></td>
            <td class="table__actions"><a href="{{ route('admin.orders.show', $order) }}" class="link">Ver</a></td>
          </tr>
        @empty
          <tr><td class="table__empty">Aún no hay pedidos.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

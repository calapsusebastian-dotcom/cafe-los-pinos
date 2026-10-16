@extends('layouts.admin')

@section('title', 'Pedido #'.$order->id)

@section('content')
  <div class="admin-head">
    <h1>Pedido #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="link">← Volver a pedidos</a>
  </div>

  <div class="panel order-detail">
    <dl class="order-detail__data">
      <div><dt>Cliente</dt><dd>{{ $order->customer_name }}</dd></div>
      <div><dt>Celular</dt><dd><a href="https://wa.me/{{ strlen($order->customer_phone) === 10 ? '57'.$order->customer_phone : $order->customer_phone }}" target="_blank" rel="noopener" class="link">{{ $order->customer_phone }}</a></dd></div>
      <div><dt>Ciudad</dt><dd>{{ $order->city }}</dd></div>
      <div><dt>Dirección</dt><dd>{{ $order->address }}</dd></div>
      <div><dt>Fecha</dt><dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd></div>
      @if ($order->notes)
        <div><dt>Notas</dt><dd>{{ $order->notes }}</dd></div>
      @endif
    </dl>

    <table class="table">
      <thead>
        <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        @foreach ($order->items as $item)
          <tr>
            <td>{{ $item->name }} <span class="muted">{{ $item->weight }}</span></td>
            <td>{{ $item->quantity }}</td>
            <td>$ {{ number_format($item->unit_price, 0, ',', '.') }}</td>
            <td>$ {{ number_format($item->lineTotal(), 0, ',', '.') }}</td>
          </tr>
        @endforeach
        <tr><td colspan="3" class="order-detail__label">Subtotal</td><td>$ {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
        <tr><td colspan="3" class="order-detail__label">Envío</td><td>{{ $order->shipping_cost > 0 ? '$ '.number_format($order->shipping_cost, 0, ',', '.') : 'Por confirmar' }}</td></tr>
        <tr><td colspan="3" class="order-detail__label"><strong>Total</strong></td><td><strong>$ {{ number_format($order->total, 0, ',', '.') }}</strong></td></tr>
      </tbody>
    </table>

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="form order-detail__status">
      @csrf
      @method('PUT')
      <div class="field">
        <label for="status">Estado del pedido</label>
        <select id="status" name="status">
          @foreach ($statuses as $option)
            <option value="{{ $option->value }}" @selected(old('status', $order->status->value) === $option->value)>{{ $option->label() }}</option>
          @endforeach
        </select>
        @error('status')<p class="field__error">{{ $message }}</p>@enderror
      </div>
      <button type="submit" class="btn-admin">Guardar estado</button>
    </form>
  </div>
@endsection

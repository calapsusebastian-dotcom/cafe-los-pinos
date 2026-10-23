<x-mail::message>
# Nuevo pedido #{{ $order->id }}

**{{ $order->customer_name }}** hizo un pedido desde el sitio.

<x-mail::table>
| Producto | Cantidad | Valor |
| :------- | :------: | ----: |
@foreach ($order->items as $item)
| Café {{ $item->name }} ({{ $item->weight }}) | {{ $item->quantity }} | $ {{ number_format($item->lineTotal(), 0, ',', '.') }} |
@endforeach
</x-mail::table>

Subtotal: $ {{ number_format($order->subtotal, 0, ',', '.') }}  
Envío: {{ $order->shipping_cost > 0 ? '$ '.number_format($order->shipping_cost, 0, ',', '.') : 'por confirmar' }}  
**Total: $ {{ number_format($order->total, 0, ',', '.') }}**

Celular: {{ $order->customer_phone }}  
Ciudad: {{ $order->city }}  
Dirección: {{ $order->address }}
@if ($order->notes)

Notas: {{ $order->notes }}
@endif

<x-mail::button :url="$url">
Ver pedido en el administrador
</x-mail::button>
</x-mail::message>

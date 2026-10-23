<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Mail\NewOrderMail;
use App\Models\Order;
use App\Models\Product;
use App\Support\SiteContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Saves the order using the prices stored in the database (never the ones
     * sent by the browser) and returns the WhatsApp link that announces it.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $content = SiteContent::all();

        $quantities = collect($request->validated('items'))
            ->groupBy('id')
            ->map(fn ($lines): int => min(50, $lines->sum('qty')));

        $products = Product::query()->visible()->whereKey($quantities->keys())->get()->keyBy('id');

        $order = DB::transaction(function () use ($request, $quantities, $products, $content): Order {
            $subtotal = $quantities->sum(fn (int $quantity, int|string $id): int => $products[$id]->price * $quantity);
            $shipping = SiteContent::shippingFor($subtotal, $content);

            $order = Order::create([
                ...$request->safe()->only(['customer_name', 'customer_phone', 'city', 'address', 'notes']),
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $subtotal + $shipping,
            ]);

            foreach ($quantities as $id => $quantity) {
                $order->items()->create([
                    'product_id' => $products[$id]->id,
                    'name' => $products[$id]->name,
                    'weight' => $products[$id]->weight,
                    'unit_price' => $products[$id]->price,
                    'quantity' => $quantity,
                ]);
            }

            return $order;
        });

        $order->load('items');

        $this->notifyOwner($order);

        return response()->json([
            'order' => $order->id,
            'whatsapp_url' => SiteContent::whatsappLink($content, $this->whatsappMessage($order)),
        ], 201);
    }

    /**
     * Emails the owner about the new order. A mail failure must never lose the
     * order or block the customer, so it is only reported.
     */
    private function notifyOwner(Order $order): void
    {
        $address = config('mail.order_notification_address');

        if (blank($address)) {
            return;
        }

        try {
            Mail::to($address)->send(new NewOrderMail($order));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    private function whatsappMessage(Order $order): string
    {
        $lines = $order->items->map(fn ($item): string => "• {$item->quantity} x Café {$item->name} ({$item->weight}) — ".$this->money($item->lineTotal()));

        $shipping = $order->shipping_cost > 0 ? $this->money($order->shipping_cost) : 'por confirmar';

        return implode("\n", [
            "Hola, soy {$order->customer_name}. Hice el pedido #{$order->id}:",
            ...$lines,
            'Subtotal: '.$this->money($order->subtotal),
            "Envío: {$shipping}",
            'Total: '.$this->money($order->total),
            '',
            "Ciudad: {$order->city}",
            "Dirección: {$order->address}",
            "Celular: {$order->customer_phone}",
            ...($order->notes ? ["Notas: {$order->notes}"] : []),
        ]);
    }

    private function money(int $value): string
    {
        return '$ '.number_format($value, 0, ',', '.');
    }
}

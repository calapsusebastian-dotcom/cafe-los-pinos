<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Support\CsvExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = OrderStatus::tryFrom((string) $request->query('estado'));
        $search = trim((string) $request->query('buscar'));

        return view('admin.orders.index', [
            'orders' => Order::query()
                ->withCount('items')
                ->when($status, fn ($query) => $query->where('status', $status))
                ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search): void {
                    $term = '%'.addcslashes($search, '%_\\').'%';

                    $query->where('customer_name', 'like', $term)
                        ->orWhere('customer_phone', 'like', $term)
                        ->orWhere('city', 'like', $term)
                        ->orWhere('id', ltrim($search, '#'));
                }))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'status' => $status,
            'search' => $search,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items'),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        return redirect()->route('admin.orders.show', $order)->with('status', 'Estado del pedido actualizado.');
    }

    public function export(Request $request): StreamedResponse
    {
        $status = OrderStatus::tryFrom((string) $request->query('estado'));

        $rows = Order::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->lazy()
            ->map(fn (Order $order): array => [
                $order->id,
                $order->created_at->format('Y-m-d H:i'),
                $order->customer_name,
                $order->customer_phone,
                $order->city,
                $order->address,
                $order->notes,
                $order->subtotal,
                $order->shipping_cost,
                $order->total,
                $order->status->label(),
            ]);

        return CsvExport::download('pedidos-'.now()->format('Y-m-d').'.csv', [
            'Pedido', 'Fecha', 'Cliente', 'Celular', 'Ciudad', 'Dirección', 'Notas', 'Subtotal', 'Envío', 'Total', 'Estado',
        ], $rows);
    }
}

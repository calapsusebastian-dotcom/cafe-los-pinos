<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscriber;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $monthStart = now()->startOfMonth();

        $monthOrders = Order::query()
            ->where('created_at', '>=', $monthStart)
            ->where('status', '!=', OrderStatus::Cancelled);

        return view('admin.dashboard', [
            'pendingOrders' => Order::query()->where('status', OrderStatus::Pending)->count(),
            'monthOrders' => (clone $monthOrders)->count(),
            'monthSales' => (int) (clone $monthOrders)->sum('total'),
            'subscribers' => Subscriber::query()->count(),
            'topProducts' => OrderItem::query()
                ->selectRaw('name, weight, SUM(quantity) as units')
                ->whereHas('order', fn ($query) => $query
                    ->where('created_at', '>=', $monthStart)
                    ->where('status', '!=', OrderStatus::Cancelled))
                ->groupBy('name', 'weight')
                ->orderByDesc('units')
                ->limit(5)
                ->get(),
            'recentOrders' => Order::query()->latest()->limit(5)->get(),
        ]);
    }
}

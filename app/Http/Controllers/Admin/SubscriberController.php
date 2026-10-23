<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Support\CsvExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(): View
    {
        return view('admin.subscribers.index', [
            'subscribers' => Subscriber::query()->latest()->get(),
        ]);
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->route('admin.subscribers.index')->with('status', 'Suscriptor eliminado.');
    }

    public function export(): StreamedResponse
    {
        $rows = Subscriber::query()
            ->latest()
            ->lazy()
            ->map(fn (Subscriber $subscriber): array => [
                $subscriber->email,
                $subscriber->created_at->format('Y-m-d H:i'),
            ]);

        return CsvExport::download('suscriptores-'.now()->format('Y-m-d').'.csv', ['Correo', 'Fecha'], $rows);
    }
}

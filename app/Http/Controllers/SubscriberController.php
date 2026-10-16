<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;

class SubscriberController extends Controller
{
    public function store(StoreSubscriberRequest $request): RedirectResponse
    {
        Subscriber::firstOrCreate(['email' => $request->validated('email')]);

        return redirect()->to(url()->previous().'#contacto')
            ->with('newsletter_status', '¡Gracias por suscribirte! Pronto recibirás nuestras novedades.');
    }
}

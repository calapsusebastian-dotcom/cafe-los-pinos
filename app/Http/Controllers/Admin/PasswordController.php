<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('admin.password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password' => $request->validated('password')]);

        return redirect()->route('admin.password.edit')->with('status', 'Contraseña actualizada.');
    }
}

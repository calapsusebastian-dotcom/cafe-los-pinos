<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteContentRequest;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.content.edit', [
            'sections' => SiteContent::sections(),
            'values' => SiteContent::all(),
        ]);
    }

    public function update(UpdateSiteContentRequest $request): RedirectResponse
    {
        SiteContent::save($request->validated());

        return redirect()->route('admin.content.edit')->with('status', 'Contenido guardado.');
    }
}

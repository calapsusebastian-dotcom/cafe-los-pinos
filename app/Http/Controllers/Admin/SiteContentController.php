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
            'images' => SiteContent::images(),
            'imageUrls' => SiteContent::imageUrls(),
            'uploadedImages' => SiteContent::uploadedImageKeys(),
        ]);
    }

    public function update(UpdateSiteContentRequest $request): RedirectResponse
    {
        SiteContent::save($request->safe()->only(array_keys(SiteContent::fields())));

        foreach (array_keys(SiteContent::images()) as $key) {
            if ($request->hasFile($key)) {
                SiteContent::saveImage($key, $request->file($key));
            } elseif ($request->boolean("remove_{$key}")) {
                SiteContent::deleteImage($key);
            }
        }

        return redirect()->route('admin.content.edit')->with('status', 'Contenido guardado.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(['is_visible' => true, 'weight' => '500 g']),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['sort_order'] ??= (int) Product::max('sort_order') + 1;
        $data['image'] = $request->file('image')->store('productos', 'public');

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['sort_order'] ??= $product->sort_order;

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($product);
            $data['image'] = $request->file('image')->store('productos', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteUploadedImage($product);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado.');
    }

    private function deleteUploadedImage(Product $product): void
    {
        if ($product->hasUploadedImage()) {
            Storage::disk('public')->delete($product->image);
        }
    }
}

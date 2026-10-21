<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->visible()
            ->when($search !== '', function ($query) use ($search): void {
                $term = '%'.addcslashes($search, '%_\\').'%';

                $query->where(fn ($inner) => $inner->where('name', 'like', $term)->orWhere('description', 'like', $term));
            })
            ->ordered()
            ->get();

        return view('home', [
            'products' => $products,
            'search' => $search,
            'content' => SiteContent::all(),
            'images' => SiteContent::imageUrls(),
        ]);
    }

    public function story(): View
    {
        return view('story', [
            'content' => SiteContent::all(),
            'images' => SiteContent::imageUrls(),
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_visible, 404);

        return view('product', [
            'product' => $product,
            'related' => Product::query()->visible()->whereKeyNot($product->getKey())->ordered()->limit(3)->get(),
            'content' => SiteContent::all(),
        ]);
    }
}

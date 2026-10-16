<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $products = Product::query()->visible()->ordered()->get();

        return response()
            ->view('sitemap', [
                'products' => $products,
                'posts' => Post::query()->published()->latestFirst()->get(),
                'lastModified' => $products->max('updated_at'),
            ])
            ->header('Content-Type', 'application/xml');
    }
}

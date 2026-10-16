<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('nuestra-historia', [HomeController::class, 'story'])->name('story');
Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('cafe/{product}', [HomeController::class, 'product'])->name('product');
Route::post('suscribirse', [SubscriberController::class, 'store'])->middleware('throttle:5,1')->name('subscribe');
Route::get('robots.txt', fn () => response("User-agent: *\nDisallow: /admin\n\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']))->name('robots');
Route::post('pedidos', [OrderController::class, 'store'])->middleware('throttle:10,1')->name('orders.store');
Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [SessionController::class, 'create'])->name('login');
        Route::post('login', [SessionController::class, 'store'])->middleware('throttle:5,1');
    });

    Route::middleware('auth')->group(function (): void {
        Route::redirect('/', '/admin/productos')->name('dashboard');
        Route::post('logout', [SessionController::class, 'destroy'])->name('logout');

        Route::resource('productos', ProductController::class)
            ->parameters(['productos' => 'product'])
            ->names('products')
            ->except('show');

        Route::get('pedidos', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('pedidos/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('pedidos/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

        Route::resource('articulos', PostController::class)
            ->parameters(['articulos' => 'post'])
            ->names('posts')
            ->except('show');

        Route::get('contenido', [SiteContentController::class, 'edit'])->name('content.edit');
        Route::get('suscriptores', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
        Route::delete('suscriptores/{subscriber}', [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');

        Route::put('contenido', [SiteContentController::class, 'update'])->name('content.update');
    });
});

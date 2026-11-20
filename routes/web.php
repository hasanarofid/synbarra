<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\OrderManagerController;
use App\Http\Controllers\Admin\ProductManagerController;
use App\Http\Controllers\Admin\RoadmapController;
use App\Http\Controllers\Admin\SeoGeoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SynbarraController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SYNBARRA TECHNOLOGIES — Corporate Website Routes
| Domain: synbarra.com
|--------------------------------------------------------------------------
*/

Route::get('/', [SynbarraController::class, 'home'])->name('home');
Route::get('/about', [SynbarraController::class, 'about'])->name('about');
Route::get('/services', [SynbarraController::class, 'services'])->name('services');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/invoicerapi', fn () => redirect()->route('products.show', ['slug' => 'invoicerapi'], 301))->name('invoicerapi');
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/blog', fn () => redirect()->route('articles.index', [], 301));
Route::get('/blog/{slug}', fn (string $slug) => redirect()->route('articles.show', ['slug' => $slug], 301));
Route::get('/approach', [SynbarraController::class, 'approach'])->name('approach');
Route::get('/portofolio', [SynbarraController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio', fn () => redirect()->route('portfolio', [], 301));
Route::get('/contact', [SynbarraController::class, 'contact'])->name('contact');
Route::post('/contact', [SynbarraController::class, 'submitContact'])->name('contact.submit');
Route::get('/sitemap.xml', [SynbarraController::class, 'sitemap'])->name('sitemap');
Route::get('/llms.txt', [SynbarraController::class, 'llmsTxt'])->name('llms');
Route::get('/template-showcase', fn () => redirect()->route('products.index', [], 301))->name('template.showcase');

/*
|--------------------------------------------------------------------------
| Admin & Management Portal Routes
|--------------------------------------------------------------------------
*/

// Redirect /admin & /dashboard based on auth state
Route::get('/admin', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
})->name('admin');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Roadmap Pengembangan & Akuisisi Klien (Sprint Tahap 1 - 5)
    Route::resource('roadmap', RoadmapController::class);
    Route::patch('/roadmap/{roadmap}/status', [RoadmapController::class, 'updateStatus'])->name('roadmap.update-status');

    // Lead Management & Inbox Prospek
    Route::resource('leads', LeadController::class);

    // Products / Service Catalog Management
    Route::resource('products', ProductManagerController::class);

    // Orders / Inquiries Management
    Route::get('/orders', [OrderManagerController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagerController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderManagerController::class, 'updateStatus'])->name('orders.update-status');

    // GEO & SEO Traffic Analytics
    Route::get('/seo-geo', [SeoGeoController::class, 'index'])->name('seo-geo.index');
    Route::post('/seo-geo/config', [SeoGeoController::class, 'updateConfig'])->name('seo-geo.config');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

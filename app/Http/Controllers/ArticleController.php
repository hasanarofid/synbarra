<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel & wawasan bisnis, keuangan, serta teknologi.
     */
    public function index(Request $request): Response
    {
        $categorySlug = $request->query('kategori');
        $search = $request->query('q');

        $categories = Category::where('type', 'post')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $query = Post::where('status', 'published')
            ->with('category:id,name,slug')
            ->latest();

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(9)->withQueryString();

        $meta = [
            'title' => 'Artikel & Wawasan Bisnis, Keuangan & Teknologi | Synbarra Technologies',
            'subtitle' => 'Panduan Praktis Manajemen Bisnis Jasa, Cashflow, Invoicing & Transformasi Digital',
            'description' => 'Kumpulan artikel informatif seputar pengelolaan keuangan bisnis jasa, manajemen proyek, tips menagih klien, UMKM, dan teknologi digital dari Synbarra Technologies.',
            'keywords' => 'artikel bisnis, manajemen keuangan umkm, tips invoice dan termin, cashflow bisnis jasa, synbarra technologies, invoicerapi',
            'og_image' => 'images/products/invoicerapi/mockup-hero.png',
            'type' => 'website',
        ];

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'currentCategory' => $categorySlug,
            'searchQuery' => $search,
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Menampilkan isi lengkap artikel.
     */
    public function show(string $slug): Response
    {
        $article = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with('category:id,name,slug')
            ->firstOrFail();

        $relatedArticles = Post::where('id', '!=', $article->id)
            ->where('status', 'published')
            ->with('category:id,name,slug')
            ->latest()
            ->take(3)
            ->get(['id', 'category_id', 'title', 'slug', 'excerpt', 'image', 'reading_time', 'created_at']);

        $meta = [
            'title' => ($article->meta_title ?: $article->title) . ' | Synbarra Technologies',
            'subtitle' => $article->category?->name ?? 'Artikel & Wawasan',
            'description' => $article->meta_description ?: $article->excerpt ?: 'Artikel informatif dari Synbarra Technologies.',
            'keywords' => "{$article->title}, {$article->category?->name}, synbarra, invoicerapi",
            'og_image' => $article->image_url ?: 'images/products/invoicerapi/mockup-hero.png',
            'type' => 'article',
        ];

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }
}

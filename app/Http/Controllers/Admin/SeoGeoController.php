<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageVisit;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SeoGeoController extends Controller
{
    public function index(Request $request): Response
    {
        $days = in_array((int) $request->query('days', 30), [7, 30, 90], true)
            ? (int) $request->query('days', 30)
            : 30;

        $since = now()->subDays($days)->startOfDay();

        $totalVisits = PageVisit::where('created_at', '>=', $since)->count();
        $humanVisits = PageVisit::where('created_at', '>=', $since)->where('visitor_type', 'human')->count();
        $googleTraffic = PageVisit::where('created_at', '>=', $since)->where('traffic_source', 'google')->count();
        $metaTraffic = PageVisit::where('created_at', '>=', $since)->where('traffic_source', 'meta')->count();
        $aiSearchTraffic = PageVisit::where('created_at', '>=', $since)->where('traffic_source', 'ai_search')->count();

        $sourceBreakdown = PageVisit::where('created_at', '>=', $since)
            ->select('traffic_source', DB::raw('COUNT(*) as total'))
            ->groupBy('traffic_source')
            ->orderByDesc('total')
            ->get();

        $dailyTrend = PageVisit::where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("SUM(CASE WHEN visitor_type = 'human' THEN 1 ELSE 0 END) as humans"),
                DB::raw("SUM(CASE WHEN visitor_type != 'human' THEN 1 ELSE 0 END) as bots")
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $topPages = PageVisit::where('created_at', '>=', $since)
            ->select(
                'path',
                DB::raw('COUNT(*) as hits'),
                DB::raw("SUM(CASE WHEN visitor_type = 'human' THEN 1 ELSE 0 END) as human_hits"),
                DB::raw("SUM(CASE WHEN visitor_type = 'aibot' THEN 1 ELSE 0 END) as ai_hits")
            )
            ->groupBy('path')
            ->orderByDesc('hits')
            ->take(10)
            ->get();

        $recentBots = PageVisit::where('visitor_type', '!=', 'human')
            ->latest('created_at')
            ->take(15)
            ->get(['id', 'path', 'visitor_type', 'bot_name', 'traffic_source', 'created_at']);

        $gaId = Setting::getValue('ga_measurement_id', '');
        $metaPixelId = Setting::getValue('meta_pixel_id', '');
        $gscCode = Setting::getValue('gsc_verification', 'google587f0f47640fdb34');

        $readiness = [
            [
                'label' => 'robots.txt & Sitemap XML',
                'status' => file_exists(public_path('robots.txt')),
                'detail' => 'https://synbarra.com/sitemap.xml & /robots.txt aktif',
            ],
            [
                'label' => 'Root /llms.txt (GEO AI Discovery)',
                'status' => true,
                'detail' => 'Tersedia di https://synbarra.com/llms.txt untuk ChatGPT, Perplexity, Gemini & Claude',
            ],
            [
                'label' => 'Google Search Console Verification',
                'status' => !empty($gscCode),
                'detail' => !empty($gscCode) ? "Terverifikasi ({$gscCode})" : 'Belum diisi',
            ],
            [
                'label' => 'Google Analytics 4 (GA4)',
                'status' => !empty($gaId),
                'detail' => !empty($gaId) ? "Aktif ({$gaId})" : 'Belum dikonfigurasi — isi Measurement ID di bawah',
            ],
            [
                'label' => 'Meta Pixel (Facebook & Instagram Ads)',
                'status' => !empty($metaPixelId),
                'detail' => !empty($metaPixelId) ? "Aktif ({$metaPixelId})" : 'Belum dikonfigurasi — isi Pixel ID di bawah',
            ],
        ];

        return Inertia::render('Admin/SeoGeo', [
            'days' => $days,
            'stats' => [
                'total_visits' => $totalVisits,
                'human_visits' => $humanVisits,
                'google_traffic' => $googleTraffic,
                'meta_traffic' => $metaTraffic,
                'ai_search_traffic' => $aiSearchTraffic,
                'published_articles' => Post::published()->count(),
                'published_products' => Product::published()->count(),
            ],
            'sourceBreakdown' => $sourceBreakdown,
            'dailyTrend' => $dailyTrend,
            'topPages' => $topPages,
            'recentBots' => $recentBots,
            'readiness' => $readiness,
            'trackingConfig' => [
                'ga_measurement_id' => $gaId,
                'meta_pixel_id' => $metaPixelId,
                'gsc_verification' => $gscCode,
            ],
        ]);
    }

    public function updateConfig(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ga_measurement_id' => 'nullable|string|max:50',
            'meta_pixel_id' => 'nullable|string|max:50',
            'gsc_verification' => 'nullable|string|max:100',
        ]);

        Setting::setValue('ga_measurement_id', trim((string) ($validated['ga_measurement_id'] ?? '')), 'text');
        Setting::setValue('meta_pixel_id', trim((string) ($validated['meta_pixel_id'] ?? '')), 'text');
        Setting::setValue('gsc_verification', trim((string) ($validated['gsc_verification'] ?? '')), 'text');

        return back()->with('success', 'Konfigurasi tracking SEO & Meta/Google berhasil disimpan.');
    }
}


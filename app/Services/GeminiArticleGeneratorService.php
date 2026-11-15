<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class GeminiArticleGeneratorService
{
    /**
     * Curated multi-domain topic matrix (business, finance, creative, construction, tech)
     * designed to naturally connect reader pain points with Synbarra InvoiceRapi.
     */
    private const TOPIC_MATRIX = [
        [
            'category' => 'Keuangan & Arus Kas',
            'slug' => 'keuangan-arus-kas',
            'image' => '/images/products/invoicerapi/mockup-dashboard.png',
            'angles' => [
                'Cara Mengelola Piutang Usaha agar Cashflow Bisnis Jasa Tetap Sehat',
                'Mengapa Banyak Bisnis Jasa Untung di Atas Kertas tapi Kekurangan Uang Tunai',
                'Strategi Mengatur Jadwal DP, Termin, dan Pelunasan agar Modal Kerja Tidak Macet',
                'Kesalahan Umum Pembukuan Tagihan pada UMKM dan Cara Memperbaikinya',
                'Tips Mengurangi Risiko Klien Telat Bayar dengan Sistem Penagihan Terstruktur',
            ],
        ],
        [
            'category' => 'Bisnis Kreatif & Event',
            'slug' => 'bisnis-kreatif-event',
            'image' => '/images/products/invoicerapi/mockup-hero.png',
            'angles' => [
                'Panduan Mengatur Pembayaran DP dan Pelunasan untuk Wedding Organizer (WO)',
                'Cara Fotografer & Videografer Profesional Mengatur Booking Fee dan Termin Proyek',
                'Manajemen Penagihan Bertahap untuk Event Organizer (EO) Menangani Klien Korporat',
                'Menghindari Pembatalan Sepihak Klien di Industri Kreatif dengan Invoice Resmi',
                'Cara Membangun Kepercayaan Klien Studio Kreatif Lewat Administrasi Tagihan yang Rapi',
            ],
        ],
        [
            'category' => 'Kontraktor & Jasa Proyek',
            'slug' => 'kontraktor-jasa-proyek',
            'image' => '/images/products/invoicerapi/mockup-mobile.png',
            'angles' => [
                'Skema Pembayaran Termin Proyek Konstruksi & Desain Interior agar Bebas Boncos',
                'Cara Menyusun Jadwal Tagihan Berdasarkan Progres Fisik Proyek Lapangan',
                'Tips Kontraktor Kecil Menjaga Hubungan Baik dengan Klien saat Menagih Termin',
                'Pentingnya Bukti Penerimaan Pembayaran (Receipt) pada Setiap Tahap Proyek',
            ],
        ],
        [
            'category' => 'UMKM & Freelance',
            'slug' => 'umkm-freelance',
            'image' => '/images/products/invoicerapi/mockup-pricing.png',
            'angles' => [
                'Etika dan Cara Elegan Menagih Invoice Jatuh Tempo ke Klien Tanpa Rasa Canggung',
                'Tips Freelancer & Konsultan Tampil Lebih Profesional di Mata Klien Perusahaan',
                'Checklist Administrasi Sebelum Memulai Proyek Jasa Baru bersama Klien',
                'Kapan Bisnis UMKM Jasa Harus Beralih dari Catatan Manual ke Sistem Terpusat',
            ],
        ],
        [
            'category' => 'Teknologi & Otomasi Bisnis',
            'slug' => 'teknologi-otomasi-bisnis',
            'image' => '/images/products/invoicerapi/mockup-hero.png',
            'angles' => [
                'Keunggulan Tautan Invoice Publik Online Dibanding Kirim File PDF Manual di WhatsApp',
                'Bagaimana Otomasi Pengingat Jatuh Tempo Membantu Mempercepat Pelunasan Klien',
                'Memilih Aplikasi Cloud SaaS yang Ringan untuk Operasional Penagihan Bisnis Jasa',
                'Digitalisasi Administrasi Klien dan Proyek untuk Tim Kecil di Era Kerja Hybrid',
            ],
        ],
    ];

    public function generateAndPublish(?string $forcedCategorySlug = null): Post
    {
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            throw new RuntimeException('GEMINI_API_KEY belum dikonfigurasi di .env / config/services.php.');
        }

        $topic = $this->pickNextTopic($forcedCategorySlug);
        $generated = $this->callGemini($apiKey, $topic['category'], $topic['angle']);
        $content = $this->ensureProductPromotion($generated['content']);

        $category = $this->resolveCategory($topic['category'], $topic['slug']);
        $slug = $this->uniqueSlug($generated['title']);

        $post = Post::create([
            'category_id' => $category->id,
            'title' => $generated['title'],
            'slug' => $slug,
            'excerpt' => $generated['excerpt'],
            'content' => $content,
            'meta_title' => Str::limit($generated['meta_title'] ?: $generated['title'], 70, ''),
            'meta_description' => Str::limit($generated['meta_description'] ?: $generated['excerpt'], 160, ''),
            'reading_time' => max(3, min(10, (int) ($generated['reading_time'] ?? 5))),
            'image' => $topic['image'],
            'status' => 'published',
            'is_featured' => false,
        ]);

        Log::info('Artikel promosi InvoiceRapi berhasil dibuat oleh Gemini.', [
            'post_id' => $post->id,
            'title' => $post->title,
            'category' => $category->name,
        ]);

        return $post;
    }

    /**
     * Select a category and angle that hasn't been used recently to ensure broad coverage.
     */
    private function pickNextTopic(?string $forcedCategorySlug = null): array
    {
        $recentPosts = Post::with('category:id,slug')
            ->latest()
            ->take(10)
            ->get();

        $recentCategorySlugs = $recentPosts->pluck('category.slug')->filter()->values()->all();
        $recentTitles = $recentPosts->pluck('title')->map(fn ($t) => mb_strtolower($t))->all();

        $candidates = self::TOPIC_MATRIX;
        if ($forcedCategorySlug) {
            $filtered = array_values(array_filter($candidates, fn ($c) => $c['slug'] === $forcedCategorySlug));
            if (!empty($filtered)) {
                $candidates = $filtered;
            }
        } else {
            $unusedCategories = array_values(array_filter(
                $candidates,
                fn ($c) => !in_array($c['slug'], array_slice($recentCategorySlugs, 0, 3), true)
            ));
            if (!empty($unusedCategories)) {
                $candidates = $unusedCategories;
            }
        }

        $chosenGroup = $candidates[array_rand($candidates)];
        $angles = $chosenGroup['angles'];

        $freshAngles = array_values(array_filter($angles, function ($angle) use ($recentTitles) {
            $needle = Str::limit(mb_strtolower($angle), 25, '');
            foreach ($recentTitles as $title) {
                if (str_contains($title, $needle)) {
                    return false;
                }
            }
            return true;
        }));

        $chosenAngle = !empty($freshAngles)
            ? $freshAngles[array_rand($freshAngles)]
            : $angles[array_rand($angles)];

        return [
            'category' => $chosenGroup['category'],
            'slug' => $chosenGroup['slug'],
            'image' => $chosenGroup['image'],
            'angle' => $chosenAngle,
        ];
    }

    private function callGemini(string $apiKey, string $categoryName, string $angle): array
    {
        $primaryModel = config('services.gemini.model', 'gemini-flash-latest');
        $models = array_values(array_unique([
            $primaryModel,
            'gemini-3.6-flash',
            'gemini-2.5-flash-lite',
            'gemini-flash-lite-latest',
        ]));

        $prompt = <<<PROMPT
Anda adalah Senior Content Strategist & Business Writer di Indonesia.
Tugas Anda menulis 1 artikel blog berkualitas tinggi, edukatif, relevan untuk pembaca umum/pelaku usaha di Indonesia (bukan sekadar bahasan teknis IT) dengan topik:
- Kategori: {$categoryName}
- Sudut Pandang / Topik Utama: {$angle}

ATURAN PENULISAN & ALGORITMA PROMOSI HALUS (SOFT-SELL):
1. Tulis dalam Bahasa Indonesia yang profesional, hangat, mudah dipahami pemilik usaha, freelancer, WO, EO, fotografer, kontraktor, dan pelaku UMKM.
2. Struktur artikel (800 - 1100 kata) dalam format HTML bersih (hanya tag <h2>, <h3>, <p>, <ul>, <li>, <strong>, <blockquote>, <a> — TANPA tag <html>, <head>, <body>, atau markdown ```). Pastikan setiap paragraf <p> singkat dan nyaman dibaca di layar (maksimal 2-4 kalimat per paragraf), gunakan subjudul <h2>/<h3> dan poin <ul><li> agar tidak menumpuk.
3. Berikan nilai edukasi nyata terlebih dahulu (tips praktis, studi kasus realistis di Indonesia, langkah-langkah yang bisa langsung diterapkan).
4. SISIPKAN PROMOSI PRODUK SECARA ALAMI (Bridge Solusi): Di bagian solusi/implementasi praktis, perkenalkan **Synbarra InvoiceRapi** (https://invoicerapi.synbarra.com/) sebagai solusi SaaS yang membantu mengelola klien, proyek, invoice otomatis, jadwal termin pembayaran (DP, Termin 1, Pelunasan), pemantauan piutang, dan tautan invoice publik (/i/{token}) tanpa klien perlu login. Sertakan minimal 1 tautan `<a href="https://invoicerapi.synbarra.com/" target="_blank" rel="noopener noreferrer">Synbarra InvoiceRapi</a>` secara natural di dalam paragraf.
5. Jangan terdengar seperti iklan hard-selling yang memaksa; buat pembaca merasa terbantu lalu tertarik mencoba paket gratis atau trial Pro 14 hari.

Kembalikan HANYA JSON valid dengan struktur persis berikut:
{
  "title": "Judul artikel yang menarik, SEO-friendly, maksimal 85 karakter",
  "excerpt": "Ringkasan menarik 1-2 kalimat maksimal 180 karakter",
  "meta_title": "SEO title maksimal 65 karakter",
  "meta_description": "Meta description SEO maksimal 155 karakter",
  "reading_time": 5,
  "content": "<h2>...</h2><p>...</p>..."
}
PROMPT;

        $lastError = null;
        $rawText = null;

        foreach ($models as $model) {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::timeout(120)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($endpoint, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.75,
                            'responseMimeType' => 'application/json',
                        ],
                    ]);

                if ($response->successful()) {
                    $rawText = data_get($response->json(), 'candidates.0.content.parts.0.text');
                    if (!empty($rawText)) {
                        break;
                    }
                    $lastError = "Respons model {$model} kosong.";
                    continue;
                }

                $lastError = "Gemini API ({$model}) error ({$response->status()}): " . $response->body();
                if (!in_array($response->status(), [404, 429, 500, 503], true)) {
                    throw new RuntimeException($lastError);
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $lastError = "Koneksi ke model {$model} gagal/timeout: " . $e->getMessage();
            }
        }

        if (empty($rawText)) {
            throw new RuntimeException($lastError ?? 'Respons Gemini kosong atau tidak memiliki kandidat teks.');
        }

        $decoded = json_decode($rawText, true);
        if (!is_array($decoded) || empty($decoded['title']) || empty($decoded['content'])) {
            throw new RuntimeException('Gagal mem-parsing JSON dari Gemini: ' . Str::limit($rawText, 300));
        }

        return $decoded;
    }

    /**
     * Algorithmic safeguard: guarantee the article contains an active link to InvoiceRapi.
     */
    private function ensureProductPromotion(string $html): string
    {
        if (str_contains($html, 'invoicerapi.synbarra.com')) {
            return $html;
        }

        $callout = <<<'HTML'
<blockquote>
    <strong>Tips Praktis:</strong> Agar pencatatan DP, termin bertahap, dan pengingat jatuh tempo berjalan lebih tertib tanpa spreadsheet manual, Anda dapat memanfaatkan <a href="https://invoicerapi.synbarra.com/" target="_blank" rel="noopener noreferrer"><strong>Synbarra InvoiceRapi</strong></a>. Klien dapat melihat rincian tagihan dan mengunduh PDF lewat tautan publik tanpa perlu login. Pelajari fitur lengkapnya di halaman <a href="/produk/invoicerapi">produk Synbarra InvoiceRapi</a>.
</blockquote>
HTML;

        return $html . "\n" . $callout;
    }

    private function resolveCategory(string $name, string $slug): Category
    {
        $tenantId = Tenant::value('id');

        return Category::firstOrCreate(
            ['slug' => $slug],
            [
                'tenant_id' => $tenantId,
                'name' => $name,
                'type' => 'post',
                'description' => "Artikel dan wawasan seputar {$name} dari Synbarra Technologies.",
                'is_active' => true,
                'order' => 1,
            ]
        );
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class InvoiceRapiSeeder extends Seeder
{
    /**
     * Seed Synbarra InvoiceRapi as flagship SaaS product in the catalog.
     */
    public function run(): void
    {
        $tenant = Tenant::first() ?? Tenant::firstOrCreate(
            ['slug' => 'synbarra-technologies'],
            [
                'name' => 'Synbarra Technologies',
                'phone_whatsapp' => '6288804958476',
                'email' => 'synbarr.techno@gmail.com',
                'address' => 'Indonesia',
                'is_active' => true,
            ]
        );

        $category = Category::updateOrCreate(
            ['slug' => 'saas-aplikasi-bisnis'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'SaaS & Aplikasi Bisnis',
                'type' => 'product',
                'description' => 'Platform cloud SaaS resmi dari Synbarra Technologies untuk manajemen operasional, invoice, dan keuangan bisnis di Indonesia.',
                'image' => '/images/products/invoicerapi/mockup-hero.png',
                'order' => 0,
                'is_active' => true,
            ]
        );

        $description = <<<'HTML'
<h3>Urus Tagihan, Jadi Lebih Rapi.</h3>
<p><strong>Synbarra InvoiceRapi</strong> adalah platform SaaS manajemen klien, proyek, invoice, termin pembayaran (DP, Termin, Pelunasan), dan pemantauan piutang yang dirancang khusus untuk bisnis jasa dan UMKM di Indonesia.</p>

<h4>Masalah yang Diselesaikan</h4>
<ul>
    <li><strong>Tagihan &amp; Termin Berantakan di Spreadsheet:</strong> Sulit melacak siapa yang sudah bayar DP, siapa yang masuk Termin 2, dan mana yang sudah jatuh tempo.</li>
    <li><strong>Canggung Menagih Klien:</strong> Tidak ada pengingat terjadwal sehingga piutang menumpuk dan arus kas (cashflow) terganggu.</li>
    <li><strong>Klien Harus Login atau Ribet Kirim PDF Manual:</strong> Dengan tautan publik aman (<code>/i/{token}</code>), klien cukup klik link untuk melihat rincian termin, instruksi rekening, dan mengunduh PDF tanpa perlu buat akun.</li>
</ul>

<h4>Cocok Untuk Bisnis Jasa Indonesia</h4>
<ul>
    <li><strong>Wedding Organizer (WO):</strong> Kelola DP vendor, gedung, katering, hingga pelunasan hari H.</li>
    <li><strong>Fotografer &amp; Videografer:</strong> Jadwalkan booking fee, hari pemotretan, dan pelunasan saat hasil selesai.</li>
    <li><strong>Event Organizer (EO):</strong> Penagihan bertahap ke klien korporat dengan bukti penerimaan pembayaran resmi.</li>
    <li><strong>Kontraktor &amp; Desain Interior:</strong> Pantau termin sesuai progres fisik proyek dari DP hingga serah terima.</li>
    <li><strong>Konsultan &amp; Freelancer:</strong> Tampil profesional dengan invoice rapi dan laporan piutang real-time.</li>
</ul>
HTML;

        $specifications = [
            'product_name' => 'Synbarra InvoiceRapi',
            'product_type' => 'Cloud SaaS Platform (Web & Mobile Friendly)',
            'version' => 'v1.0 Production',
            'release_status' => 'Live & Ready to Use',
            'primary_persona' => 'WO, Fotografer, EO, Kontraktor, Konsultan & UMKM Jasa',
            'tech_stack' => [
                'Laravel 13',
                'Inertia.js v2',
                'Vue 3 + TypeScript',
                'Tailwind CSS',
                'MySQL',
                'Cloud SaaS',
            ],
            'tiers' => [
                'gratis' => [
                    'name' => 'Gratis (Starter)',
                    'price' => 0,
                    'price_formatted' => 'Gratis',
                    'badge' => 'Mulai Tanpa Biaya',
                    'features' => [
                        '5 invoice / bulan',
                        '10 klien terdaftar',
                        'Unduh invoice PDF instan',
                        'Dashboard ringkasan dasar',
                        '1 akun pengguna',
                    ],
                ],
                'pro' => [
                    'name' => 'Pro (Bulanan)',
                    'price' => 99000,
                    'price_formatted' => 'Rp 99.000 / bulan',
                    'compare_at_price' => 149000,
                    'badge' => 'Paling Populer',
                    'features' => [
                        'Uji coba gratis 14 hari tanpa kartu kredit',
                        '100 invoice / bulan',
                        'Unlimited klien & proyek',
                        'Atur jadwal DP & multi-termin',
                        'Pengingat jatuh tempo otomatis',
                        'Laporan piutang lengkap',
                        '2 akun pengguna',
                        'Tautan invoice publik kustom (/i/{token})',
                    ],
                ],
                'bisnis' => [
                    'name' => 'Bisnis (Tim)',
                    'price' => 199000,
                    'price_formatted' => 'Rp 199.000 / bulan',
                    'badge' => 'Untuk Tim & Agency',
                    'features' => [
                        '500 invoice / bulan',
                        '5 akun pengguna (Owner, Admin, Staff)',
                        'Laporan analitik lanjutan & ekspor CSV',
                        'Pengingat terjadwal via Email & WhatsApp',
                        'Dukungan prioritas dari tim Synbarra',
                        'Audit log transaksi finansial',
                    ],
                ],
            ],
            'key_modules' => [
                'Dashboard Finansial & Grafik Pendapatan Real-time',
                'Manajemen Klien & Proyek Terintegrasi',
                'Invoice Otomatis + Multi-Termin (DP, Termin, Pelunasan)',
                'Pencatatan Pembayaran Parsial & Penuh + Receipt PDF',
                'Halaman Publik Invoice Klien Tanpa Login (/i/{token})',
                'Pengingat Jatuh Tempo via WhatsApp & Email',
            ],
            'demo_urls' => [
                'live_demo' => 'https://invoicerapi.synbarra.com/',
                'documentation' => 'https://invoicerapi.synbarra.com/#fitur',
            ],
            'license_summary' => 'Langganan Cloud SaaS bulanan atau tahunan (hemat 30%). Tersedia paket Gratis permanen dan uji coba Pro 14 hari tanpa kartu kredit.',
        ];

        Product::updateOrCreate(
            ['slug' => 'invoicerapi'],
            [
                'tenant_id' => $tenant->id,
                'category_id' => $category->id,
                'name' => 'Synbarra InvoiceRapi — Aplikasi Invoice, DP & Termin Pembayaran',
                'sku' => 'SYN-SAAS-INV01',
                'price' => 99000,
                'compare_at_price' => 149000,
                'price_range' => 'Gratis s/d Rp 199.000/bln',
                'stock' => 999,
                'weight_grams' => 0,
                'material' => 'Cloud SaaS (https://invoicerapi.synbarra.com)',
                'dimensions' => 'Uji Coba Pro Gratis 14 Hari',
                'short_description' => 'Platform SaaS manajemen klien, proyek, invoice, termin pembayaran (DP, Termin, Pelunasan), dan pemantauan piutang untuk bisnis jasa di Indonesia.',
                'description' => $description,
                'specifications' => $specifications,
                'thumbnail' => '/images/products/invoicerapi/mockup-hero.png',
                'images' => [
                    '/images/products/invoicerapi/mockup-hero.png',
                    '/images/products/invoicerapi/mockup-dashboard.png',
                    '/images/products/invoicerapi/mockup-mobile.png',
                    '/images/products/invoicerapi/mockup-pricing.png',
                ],
                'is_featured' => true,
                'status' => 'published',
                'order' => 0,
            ]
        );
    }
}


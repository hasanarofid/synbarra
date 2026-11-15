<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import {
  ArrowRight,
  ExternalLink,
  Check,
  CheckCircle2,
  FileText,
  Layers,
  Bell,
  BarChart3,
  Smartphone,
  ShieldCheck,
  Sparkles,
  ChevronRight,
  Users,
  Clock,
  Headset,
} from '@lucide/vue';

interface Props {
  product?: Record<string, any>;
  meta?: {
    title: string;
    subtitle?: string;
    description: string;
    og_image: string;
  };
}

defineProps<Props>();

const isAnnual = ref(false);
const activePreview = ref<'dashboard' | 'mobile' | 'pricing'>('dashboard');

const previewTabs = [
  {
    id: 'dashboard' as const,
    label: 'Dashboard Finansial',
    image: '/images/products/invoicerapi/mockup-dashboard.png',
    caption: 'Pantau total tagihan, pembayaran masuk, piutang aktif, dan invoice overdue dalam satu layar.',
  },
  {
    id: 'mobile' as const,
    label: 'Akses Mobile Praktis',
    image: '/images/products/invoicerapi/mockup-mobile.png',
    caption: 'Buat invoice, cek status DP/termin, dan kirim tautan tagihan ke klien langsung dari smartphone.',
  },
  {
    id: 'pricing' as const,
    label: 'Skema Paket Fleksibel',
    image: '/images/products/invoicerapi/mockup-pricing.png',
    caption: 'Mulai gratis selamanya atau upgrade ke Pro/Bisnis sesuai pertumbuhan bisnis jasa Anda.',
  },
];

const features = [
  {
    title: 'Invoice Profesional Instan',
    description: 'Buat invoice dengan penomoran otomatis, logo bisnis, rincian item, dan ekspor PDF siap kirim dalam hitungan detik.',
    icon: FileText,
  },
  {
    title: 'Atur DP, Termin & Pelunasan',
    description: 'Pecah satu nilai proyek menjadi jadwal pembayaran bertahap (misal DP 30%, Termin 1 40%, Pelunasan 30%) dengan jatuh tempo jelas.',
    icon: Layers,
  },
  {
    title: 'Tautan Publik Tanpa Login',
    description: 'Kirim link unik (/i/{token}) ke klien. Klien langsung lihat status termin, rekening pembayaran, dan unduh PDF tanpa buat akun.',
    icon: Smartphone,
  },
  {
    title: 'Pantau Piutang & Pengingat',
    description: 'Deteksi tagihan mendekati jatuh tempo atau overdue secara real-time dan kirim pengingat via WhatsApp atau Email tanpa canggung.',
    icon: Bell,
  },
];

const personas = [
  {
    title: 'Wedding Organizer (WO)',
    tag: 'Spesialis WO',
    description: 'Kelola DP gedung, katering, MUA, hingga pelunasan hari H tanpa spreadsheet manual yang berceceran.',
  },
  {
    title: 'Fotografer & Videografer',
    tag: 'Creative Studio',
    description: 'Atur jadwal termin pemotretan: Booking fee 30%, Hari-H 40%, dan Pelunasan 30% saat album selesai.',
  },
  {
    title: 'Event Organizer (EO)',
    tag: 'Corporate Event',
    description: 'Penagihan bertahap ke klien korporat lengkap dengan bukti penerimaan pembayaran (receipt) dan laporan piutang.',
  },
  {
    title: 'Kontraktor & Interior',
    tag: 'Proyek Bertahap',
    description: 'Sinkronkan termin pembayaran sesuai progres fisik proyek konstruksi dan desain interior hingga serah terima.',
  },
];

const pricingPlans = [
  {
    id: 'gratis',
    name: 'Gratis',
    monthlyPrice: 'Rp 0',
    annualPrice: 'Rp 0',
    period: '/bulan',
    popular: false,
    ctaText: 'Mulai Gratis',
    ctaHref: 'https://invoicerapi.synbarra.com/register',
    features: [
      '5 invoice / bulan',
      '10 klien terdaftar',
      'Unduh invoice PDF instan',
      'Dashboard ringkasan dasar',
      '1 akun pengguna',
    ],
  },
  {
    id: 'pro',
    name: 'Pro',
    monthlyPrice: 'Rp 99.000',
    annualPrice: 'Rp 69.000',
    period: '/bulan',
    popular: true,
    badge: 'Paling Populer',
    ctaText: 'Coba Pro Gratis 14 Hari',
    ctaHref: 'https://invoicerapi.synbarra.com/register?plan=pro',
    features: [
      '100 invoice / bulan',
      'Unlimited klien & proyek',
      'Atur jadwal DP & multi-termin',
      'Pengingat jatuh tempo otomatis',
      'Laporan piutang lengkap',
      '2 akun pengguna',
      'Tautan invoice publik kustom',
    ],
  },
  {
    id: 'bisnis',
    name: 'Bisnis',
    monthlyPrice: 'Rp 199.000',
    annualPrice: 'Rp 139.000',
    period: '/bulan',
    popular: false,
    ctaText: 'Pilih Paket Bisnis',
    ctaHref: 'https://invoicerapi.synbarra.com/register?plan=bisnis',
    features: [
      '500 invoice / bulan',
      '5 akun pengguna (Owner, Admin, Staff)',
      'Laporan analitik lanjutan & ekspor CSV',
      'Pengingat terjadwal via Email & WA',
      'Dukungan prioritas tim Synbarra',
      'Audit log transaksi finansial',
    ],
  },
];

const consultWaUrl = `https://wa.me/${BRAND.contact.whatsapp}?text=${encodeURIComponent(
  'Halo Synbarra Technologies, saya tertarik dengan produk Synbarra InvoiceRapi (https://invoicerapi.synbarra.com/) dan ingin konsultasi penggunaannya untuk bisnis saya.'
)}`;
</script>

<template>
  <AppLayout
    :title="meta?.title || 'Synbarra InvoiceRapi — Urus Tagihan, Jadi Lebih Rapi | Synbarra Technologies'"
    :description="meta?.description || 'Solusi SaaS manajemen klien, proyek, invoice, termin pembayaran (DP, Termin, Pelunasan), dan pemantauan piutang untuk bisnis jasa di Indonesia.'"
    :image="meta?.og_image || '/images/products/invoicerapi/mockup-hero.png'"
  >
    <!-- Breadcrumb -->
    <div class="bg-slate-50 border-b border-slate-200/80 py-3.5">
      <Container>
        <nav class="flex items-center gap-2 text-xs text-slate-500">
          <Link href="/" class="hover:text-[#01232B] transition-colors">Home</Link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400" />
          <Link href="/produk" class="hover:text-[#01232B] transition-colors">Produk</Link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400" />
          <span class="text-slate-900 font-semibold">Synbarra InvoiceRapi</span>
        </nav>
      </Container>
    </div>

    <!-- 1. HERO PROMOSI INVOICERAPI -->
    <section class="relative pt-12 pb-16 lg:pt-16 lg:pb-24 bg-gradient-to-br from-[#011E26] via-[#01232B] to-[#083B44] text-white overflow-hidden">
      <div class="absolute top-0 right-0 w-[520px] h-[520px] bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-[420px] h-[420px] bg-[#00F1DE]/15 rounded-full blur-3xl pointer-events-none"></div>

      <Container class="relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
          <div class="lg:col-span-6 space-y-6">
            <div class="inline-flex items-center gap-3 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm">
              <img
                src="/images/products/invoicerapi/invoicerapi-icon.png"
                alt="Logo InvoiceRapi"
                class="w-5 h-5 rounded object-contain"
              />
              <span class="text-xs font-semibold tracking-wide text-[#00F1DE]">
                Produk SaaS Resmi Synbarra Technologies
              </span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-[1.14]">
              Urus Tagihan, DP &amp; Termin Klien
              <span class="text-[#00F1DE]">Jadi Lebih Rapi.</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-300 leading-relaxed max-w-xl">
              <strong class="text-white">Synbarra InvoiceRapi</strong> membantu bisnis jasa di Indonesia mengelola invoice, jadwal DP, termin bertahap, hingga pemantauan piutang secara otomatis tanpa spreadsheet berantakan.
            </p>

            <div class="flex flex-col sm:flex-row gap-3.5 pt-2">
              <a
                href="https://invoicerapi.synbarra.com/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-[#01232B] bg-[#00F1DE] hover:bg-[#04D2C5] shadow-lg shadow-[#00F1DE]/20 transition-all transform hover:-translate-y-0.5"
              >
                <span>Kunjungi Website InvoiceRapi</span>
                <ExternalLink class="w-4 h-4" />
              </a>
              <a
                :href="consultWaUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/15 border border-white/20 transition-all"
              >
                <Headset class="w-4 h-4 text-[#00F1DE]" />
                <span>Konsultasi Demo via WA</span>
              </a>
            </div>

            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 pt-3 text-xs text-slate-300">
              <span class="inline-flex items-center gap-1.5">
                <CheckCircle2 class="w-4 h-4 text-[#00F1DE]" />
                Paket Gratis Permanen
              </span>
              <span class="inline-flex items-center gap-1.5">
                <CheckCircle2 class="w-4 h-4 text-[#00F1DE]" />
                Trial Pro 14 Hari Tanpa Kartu Kredit
              </span>
              <span class="inline-flex items-center gap-1.5">
                <CheckCircle2 class="w-4 h-4 text-[#00F1DE]" />
                Cloud SaaS Siap Pakai
              </span>
            </div>
          </div>

          <div class="lg:col-span-6">
            <div class="relative rounded-2xl overflow-hidden border border-white/15 bg-white/5 p-2 shadow-2xl">
              <img
                src="/images/products/invoicerapi/mockup-hero.png"
                alt="Preview Aplikasi Synbarra InvoiceRapi di Desktop dan Mobile"
                class="w-full h-auto rounded-xl object-cover"
              />
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 2. FITUR UNGGULAN -->
    <section class="py-16 lg:py-24 bg-white">
      <Container>
        <div class="max-w-2xl mx-auto text-center mb-14">
          <span class="text-xs font-bold uppercase tracking-widest text-violet-700">Fitur Utama</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Semua yang Dibutuhkan untuk Menagih Lebih Profesional
          </h2>
          <p class="text-sm sm:text-base text-slate-600 mt-3">
            Dirancang khusus untuk alur kerja bisnis jasa Indonesia yang menggunakan sistem uang muka (DP) dan pembayaran bertahap.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="feat in features"
            :key="feat.title"
            class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-violet-300 hover:shadow-lg transition-all duration-200"
          >
            <div class="w-11 h-11 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center mb-4">
              <component :is="feat.icon" class="w-5 h-5" />
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-2">{{ feat.title }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ feat.description }}</p>
          </div>
        </div>
      </Container>
    </section>

    <!-- 3. INTERACTIVE PREVIEW MOCKUPS -->
    <section class="py-16 lg:py-24 bg-slate-50 border-y border-slate-200/80">
      <Container>
        <div class="max-w-2xl mx-auto text-center mb-10">
          <span class="text-xs font-bold uppercase tracking-widest text-[#01232B]">Tampilan Sistem</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Intip Antarmuka Synbarra InvoiceRapi
          </h2>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap justify-center gap-2 mb-8">
          <button
            v-for="tab in previewTabs"
            :key="tab.id"
            type="button"
            @click="activePreview = tab.id"
            :class="[
              'px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all',
              activePreview === tab.id
                ? 'bg-[#01232B] text-[#00F1DE] shadow-md'
                : 'bg-white text-slate-600 border border-slate-200 hover:text-slate-900'
            ]"
          >
            {{ tab.label }}
          </button>
        </div>

        <div class="max-w-4xl mx-auto">
          <template v-for="tab in previewTabs" :key="tab.id">
            <div v-if="activePreview === tab.id" class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-5 shadow-xl">
              <img
                :src="tab.image"
                :alt="tab.label"
                class="w-full h-auto rounded-xl object-contain max-h-[480px] mx-auto bg-slate-50"
              />
              <p class="text-center text-sm text-slate-600 mt-4 font-medium">
                {{ tab.caption }}
              </p>
            </div>
          </template>
        </div>
      </Container>
    </section>

    <!-- 4. TARGET BISNIS JASA -->
    <section class="py-16 lg:py-24 bg-white">
      <Container>
        <div class="max-w-2xl mx-auto text-center mb-12">
          <span class="text-xs font-bold uppercase tracking-widest text-violet-700">Untuk Siapa?</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Solusi Tepat untuk Bisnis Berbasis Proyek &amp; Jasa
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="persona in personas"
            :key="persona.title"
            class="p-6 rounded-2xl border border-slate-200 bg-white hover:shadow-md transition-all"
          >
            <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold bg-violet-100 text-violet-800 mb-3">
              {{ persona.tag }}
            </span>
            <h3 class="text-base font-bold text-slate-900 mb-2">{{ persona.title }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ persona.description }}</p>
          </div>
        </div>
      </Container>
    </section>

    <!-- 5. HARGA & PAKET LANGGANAN -->
    <section class="py-16 lg:py-24 bg-slate-50 border-t border-slate-200/80">
      <Container>
        <div class="max-w-2xl mx-auto text-center mb-10">
          <span class="text-xs font-bold uppercase tracking-widest text-violet-700">Paket Harga Transparan</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Mulai Gratis, Upgrade Kapan Saja
          </h2>

          <div class="inline-flex items-center gap-2 p-1.5 rounded-full bg-white border border-slate-200 mt-6">
            <button
              type="button"
              @click="isAnnual = false"
              :class="[
                'px-5 py-1.5 rounded-full text-xs font-semibold transition',
                !isAnnual ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              Bulanan
            </button>
            <button
              type="button"
              @click="isAnnual = true"
              :class="[
                'px-5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition',
                isAnnual ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              <span>Tahunan</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Hemat 30%</span>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch">
          <div
            v-for="plan in pricingPlans"
            :key="plan.id"
            :class="[
              'rounded-2xl p-7 flex flex-col justify-between bg-white relative transition-all',
              plan.popular
                ? 'border-2 border-violet-600 shadow-xl ring-4 ring-violet-100/70'
                : 'border border-slate-200 shadow-sm'
            ]"
          >
            <div
              v-if="plan.popular"
              class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-xs font-bold text-white bg-violet-700"
            >
              {{ plan.badge }}
            </div>

            <div>
              <h3 class="text-xl font-bold text-slate-900">{{ plan.name }}</h3>
              <div class="mt-4 flex items-baseline gap-1">
                <span class="text-3xl font-extrabold text-slate-900">
                  {{ isAnnual ? plan.annualPrice : plan.monthlyPrice }}
                </span>
                <span class="text-xs text-slate-500">{{ plan.period }}</span>
              </div>
              <p v-if="isAnnual && plan.id !== 'gratis'" class="text-[11px] text-emerald-600 font-medium mt-1">
                Ditagih tahunan (hemat 30%)
              </p>

              <ul class="mt-6 space-y-3 pt-6 border-t border-slate-100 text-sm text-slate-600">
                <li v-for="feat in plan.features" :key="feat" class="flex items-start gap-2.5">
                  <Check class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                  <span>{{ feat }}</span>
                </li>
              </ul>
            </div>

            <a
              :href="plan.ctaHref"
              target="_blank"
              rel="noopener noreferrer"
              :class="[
                'mt-8 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold transition-all',
                plan.popular
                  ? 'bg-violet-700 text-white hover:bg-violet-800 shadow-md'
                  : 'bg-[#01232B] text-white hover:bg-[#083B44]'
              ]"
            >
              <span>{{ plan.ctaText }}</span>
              <ArrowRight class="w-4 h-4" />
            </a>
          </div>
        </div>
      </Container>
    </section>

    <!-- 6. FINAL CTA -->
    <section class="py-16 bg-[#01232B] text-white">
      <Container>
        <div class="max-w-3xl mx-auto text-center space-y-5">
          <h2 class="text-2xl sm:text-3xl font-extrabold">
            Siap Merapikan Penagihan &amp; Cashflow Bisnis Anda?
          </h2>
          <p class="text-sm sm:text-base text-slate-300">
            Coba langsung <strong class="text-white">Synbarra InvoiceRapi</strong> sekarang secara gratis di <span class="text-[#00F1DE] font-mono">invoicerapi.synbarra.com</span>.
          </p>
          <div class="flex flex-wrap justify-center gap-4 pt-2">
            <a
              href="https://invoicerapi.synbarra.com/"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-sm font-bold text-[#01232B] bg-[#00F1DE] hover:bg-[#04D2C5] transition-all"
            >
              <span>Buka Aplikasi InvoiceRapi</span>
              <ExternalLink class="w-4 h-4" />
            </a>
            <Link
              href="/produk"
              class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/15 border border-white/20 transition-all"
            >
              <span>Lihat Produk Synbarra Lainnya</span>
            </Link>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>


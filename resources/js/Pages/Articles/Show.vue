<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import {
  ArrowLeft,
  ArrowRight,
  Clock,
  Calendar,
  ExternalLink,
  Sparkles,
  CheckCircle2,
} from '@lucide/vue';

interface CategoryItem {
  id: number;
  name: string;
  slug: string;
}

interface ArticleDetail {
  id: number;
  title: string;
  slug: string;
  excerpt?: string;
  content: string;
  image_url?: string;
  reading_time?: number;
  created_at?: string;
  category?: CategoryItem;
}

defineProps<{
  article: ArticleDetail;
  relatedArticles: ArticleDetail[];
  meta?: {
    title: string;
    description: string;
    og_image: string;
  };
}>();

const formatDate = (dateStr?: string) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
};
</script>

<template>
  <AppLayout
    :title="meta?.title || `${article.title} | Synbarra Technologies`"
    :description="meta?.description || article.excerpt || 'Artikel informatif dari Synbarra Technologies.'"
    :image="meta?.og_image || article.image_url || '/images/products/invoicerapi/mockup-hero.png'"
    type="article"
  >
    <article class="py-12 lg:py-16 bg-white">
      <Container>
        <div class="max-w-4xl mx-auto">
          <!-- Back Link -->
          <div class="mb-6">
            <Link
              href="/artikel"
              class="inline-flex items-center gap-2 text-xs font-bold text-[#01232B] hover:text-[#04D2C5] transition-colors"
            >
              <ArrowLeft class="w-4 h-4" />
              <span>Kembali ke Semua Artikel</span>
            </Link>
          </div>

          <!-- Header -->
          <header class="space-y-4 mb-8">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#00F1DE]/15 text-[#01232B] border border-[#04D2C5]/30">
              {{ article.category?.name || 'Wawasan Bisnis' }}
            </span>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
              {{ article.title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-medium">
              <span class="inline-flex items-center gap-1.5">
                <Clock class="w-4 h-4 text-[#04D2C5]" />
                {{ article.reading_time || 4 }} Menit Baca
              </span>
              <span v-if="article.created_at" class="inline-flex items-center gap-1.5">
                <Calendar class="w-4 h-4 text-[#04D2C5]" />
                {{ formatDate(article.created_at) }}
              </span>
            </div>
          </header>

          <!-- Featured Image -->
          <div
            v-if="article.image_url"
            class="rounded-2xl overflow-hidden shadow-lg mb-10 border border-slate-200 max-h-[440px] bg-slate-50"
          >
            <img
              :src="article.image_url"
              :alt="article.title"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Excerpt -->
          <p
            v-if="article.excerpt"
            class="text-base sm:text-lg text-slate-700 font-medium leading-relaxed p-5 rounded-xl bg-slate-50 border-l-4 border-[#00F1DE] mb-8"
          >
            {{ article.excerpt }}
          </p>

          <!-- Article Body -->
          <div
            class="prose prose-slate prose-lg max-w-none prose-headings:font-bold prose-headings:text-slate-900 prose-a:text-violet-700 hover:prose-a:text-violet-800 prose-strong:text-slate-900 leading-relaxed"
            v-html="article.content"
          ></div>

          <!-- Product Promotion CTA Box -->
          <div class="mt-14 p-7 sm:p-8 rounded-2xl bg-gradient-to-br from-[#011E26] to-[#083B44] text-white shadow-xl">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
              <div class="space-y-2.5 max-w-xl">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#00F1DE]">
                  <Sparkles class="w-3.5 h-3.5" />
                  Solusi Praktis dari Synbarra
                </span>
                <h3 class="text-xl font-extrabold">
                  Kelola Invoice, DP &amp; Termin Pembayaran Lebih Rapi dengan InvoiceRapi
                </h3>
                <p class="text-sm text-slate-300 leading-relaxed">
                  Cocok untuk WO, Fotografer, EO, Kontraktor, Konsultan, dan UMKM Jasa. Kirim tautan invoice tanpa klien perlu login dan pantau piutang secara real-time.
                </p>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-300 pt-1">
                  <span class="inline-flex items-center gap-1">
                    <CheckCircle2 class="w-3.5 h-3.5 text-[#00F1DE]" />
                    Paket Gratis Permanen
                  </span>
                  <span class="inline-flex items-center gap-1">
                    <CheckCircle2 class="w-3.5 h-3.5 text-[#00F1DE]" />
                    Trial Pro 14 Hari
                  </span>
                </div>
              </div>
              <div class="flex flex-col sm:flex-row md:flex-col gap-3 shrink-0 w-full md:w-auto">
                <a
                  href="https://invoicerapi.synbarra.com/"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#00F1DE] text-[#01232B] text-xs font-bold hover:bg-[#04D2C5] transition-all"
                >
                  <span>Coba InvoiceRapi Sekarang</span>
                  <ExternalLink class="w-4 h-4" />
                </a>
                <Link
                  href="/produk/invoicerapi"
                  class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 text-white text-xs font-semibold border border-white/20 hover:bg-white/15 transition-all"
                >
                  <span>Lihat Detail Fitur</span>
                </Link>
              </div>
            </div>
          </div>

          <!-- Related Articles -->
          <div v-if="relatedArticles.length > 0" class="mt-16 pt-12 border-t border-slate-200">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6">Artikel Terkait Lainnya</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <Link
                v-for="rel in relatedArticles"
                :key="rel.id"
                :href="`/artikel/${rel.slug}`"
                class="group rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition-all bg-white flex flex-col justify-between"
              >
                <div>
                  <div class="h-36 bg-slate-100 overflow-hidden">
                    <img
                      :src="rel.image_url || '/images/products/invoicerapi/mockup-hero.png'"
                      :alt="rel.title"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                  <div class="p-4 space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#04D2C5]">
                      {{ rel.category?.name || 'Artikel' }}
                    </span>
                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-[#01232B] line-clamp-2">
                      {{ rel.title }}
                    </h4>
                  </div>
                </div>
                <div class="px-4 pb-4 flex items-center gap-1 text-xs font-semibold text-[#01232B]">
                  <span>Baca</span>
                  <ArrowRight class="w-3.5 h-3.5" />
                </div>
              </Link>
            </div>
          </div>
        </div>
      </Container>
    </article>
  </AppLayout>
</template>

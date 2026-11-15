<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import {
  Search,
  Clock,
  ArrowRight,
  BookOpen,
  Sparkles,
  ExternalLink,
} from '@lucide/vue';

interface CategoryItem {
  id: number;
  name: string;
  slug: string;
}

interface ArticleItem {
  id: number;
  title: string;
  slug: string;
  excerpt?: string;
  image_url?: string;
  reading_time?: number;
  created_at?: string;
  category?: CategoryItem;
}

interface PaginatedArticles {
  data: ArticleItem[];
  current_page: number;
  last_page: number;
  total: number;
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
}

const props = defineProps<{
  articles: PaginatedArticles;
  categories: CategoryItem[];
  currentCategory?: string | null;
  searchQuery?: string;
  meta?: {
    title: string;
    description: string;
    og_image: string;
  };
}>();

const searchInput = ref(props.searchQuery || '');

const handleSearch = () => {
  router.get('/artikel', {
    kategori: props.currentCategory || undefined,
    q: searchInput.value || undefined,
  }, { preserveState: true });
};

const filterByCategory = (slug?: string | null) => {
  router.get('/artikel', {
    kategori: slug || undefined,
    q: searchInput.value || undefined,
  }, { preserveState: true });
};

const formatDate = (dateStr?: string) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};
</script>

<template>
  <AppLayout
    :title="meta?.title || 'Artikel & Wawasan Bisnis | Synbarra Technologies'"
    :description="meta?.description || 'Kumpulan artikel informatif seputar bisnis jasa, manajemen keuangan, UMKM, dan teknologi dari Synbarra Technologies.'"
    :image="meta?.og_image || '/images/products/invoicerapi/mockup-hero.png'"
  >
    <!-- Header Banner -->
    <section class="relative pt-14 pb-12 bg-gradient-to-br from-[#011E26] via-[#01232B] to-[#083B44] text-white overflow-hidden">
      <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#00F1DE]/10 rounded-full blur-3xl pointer-events-none"></div>
      <Container class="relative z-10">
        <div class="max-w-3xl space-y-4">
          <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-[#00F1DE]/15 text-[#00F1DE] border border-[#00F1DE]/25">
            <BookOpen class="w-3.5 h-3.5" />
            Blog &amp; Wawasan Bisnis
          </span>
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            Artikel, Strategi Bisnis &amp; Panduan <span class="text-[#00F1DE]">Manajemen Tagihan</span>
          </h1>
          <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl">
            Insight praktis untuk pemilik bisnis jasa, freelancer, UMKM, hingga developer mengenai pengelolaan arus kas, piutang, termin proyek, dan otomasi operasional.
          </p>
        </div>

        <!-- Search & Filter Bar -->
        <div class="mt-8 flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
          <form @submit.prevent="handleSearch" class="relative w-full md:max-w-md">
            <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input
              v-model="searchInput"
              type="text"
              placeholder="Cari topik artikel (misal: termin, cashflow, WO, invoice)..."
              class="w-full pl-10 pr-24 py-2.5 rounded-xl bg-white/10 border border-white/20 text-sm text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#00F1DE]"
            />
            <button
              type="submit"
              class="absolute right-1.5 top-1/2 -translate-y-1/2 px-3.5 py-1.5 rounded-lg bg-[#00F1DE] text-[#01232B] text-xs font-bold hover:bg-[#04D2C5] transition-colors"
            >
              Cari
            </button>
          </form>

          <div class="flex flex-wrap gap-2">
            <button
              type="button"
              @click="filterByCategory(null)"
              :class="[
                'px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all',
                !currentCategory
                  ? 'bg-[#00F1DE] text-[#01232B]'
                  : 'bg-white/10 text-slate-200 hover:bg-white/20 border border-white/15'
              ]"
            >
              Semua Topik
            </button>
            <button
              v-for="cat in categories"
              :key="cat.id"
              type="button"
              @click="filterByCategory(cat.slug)"
              :class="[
                'px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all',
                currentCategory === cat.slug
                  ? 'bg-[#00F1DE] text-[#01232B]'
                  : 'bg-white/10 text-slate-200 hover:bg-white/20 border border-white/15'
              ]"
            >
              {{ cat.name }}
            </button>
          </div>
        </div>
      </Container>
    </section>

    <!-- Articles Grid -->
    <section class="py-14 lg:py-20 bg-slate-50">
      <Container>
        <div v-if="articles.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article
            v-for="post in articles.data"
            :key="post.id"
            class="group rounded-2xl bg-white border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl hover:border-[#04D2C5]/60 transition-all duration-300 flex flex-col justify-between"
          >
            <div>
              <Link :href="`/artikel/${post.slug}`" class="block relative h-52 overflow-hidden bg-slate-100">
                <img
                  :src="post.image_url || '/images/products/invoicerapi/mockup-hero.png'"
                  :alt="post.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div class="absolute top-3 left-3">
                  <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-[#01232B]/90 text-[#00F1DE] backdrop-blur-xs">
                    {{ post.category?.name || 'Wawasan Bisnis' }}
                  </span>
                </div>
              </Link>

              <div class="p-6 space-y-3">
                <div class="flex items-center justify-between text-xs text-slate-500">
                  <span class="inline-flex items-center gap-1.5">
                    <Clock class="w-3.5 h-3.5 text-[#04D2C5]" />
                    {{ post.reading_time || 4 }} Menit Baca
                  </span>
                  <span v-if="post.created_at">{{ formatDate(post.created_at) }}</span>
                </div>

                <h2 class="text-lg font-bold text-slate-900 group-hover:text-[#01232B] leading-snug line-clamp-2">
                  <Link :href="`/artikel/${post.slug}`">
                    {{ post.title }}
                  </Link>
                </h2>

                <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">
                  {{ post.excerpt }}
                </p>
              </div>
            </div>

            <div class="px-6 pb-6 pt-3 border-t border-slate-100">
              <Link
                :href="`/artikel/${post.slug}`"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#01232B] hover:text-[#04D2C5] transition-colors"
              >
                <span>Baca Selengkapnya</span>
                <ArrowRight class="w-3.5 h-3.5" />
              </Link>
            </div>
          </article>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200">
          <BookOpen class="w-10 h-10 text-slate-400 mx-auto mb-3" />
          <h3 class="text-lg font-bold text-slate-900">Belum Ada Artikel Ditemukan</h3>
          <p class="text-sm text-slate-500 mt-1">
            Coba ubah kata kunci pencarian atau pilih kategori topik lainnya.
          </p>
          <button
            type="button"
            @click="() => { searchInput = ''; filterByCategory(null); }"
            class="mt-5 px-5 py-2 rounded-xl bg-[#01232B] text-white text-xs font-semibold hover:bg-[#083B44]"
          >
            Reset Filter
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="articles.last_page > 1" class="mt-12 flex justify-center gap-1.5 flex-wrap">
          <template v-for="(link, idx) in articles.links" :key="idx">
            <Link
              v-if="link.url"
              :href="link.url"
              v-html="link.label"
              :class="[
                'px-3.5 py-2 rounded-lg text-xs font-semibold transition-all',
                link.active
                  ? 'bg-[#01232B] text-[#00F1DE]'
                  : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'
              ]"
            />
            <span
              v-else
              v-html="link.label"
              class="px-3.5 py-2 rounded-lg text-xs text-slate-400 bg-white border border-slate-100"
            />
          </template>
        </div>

        <!-- Promo Banner InvoiceRapi -->
        <div class="mt-16 rounded-2xl bg-gradient-to-r from-[#011E26] via-[#01232B] to-[#0A4D4F] p-8 sm:p-10 text-white flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 shadow-xl">
          <div class="space-y-2 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#00F1DE]">
              <Sparkles class="w-3.5 h-3.5" />
              Produk Unggulan Synbarra
            </span>
            <h3 class="text-xl sm:text-2xl font-extrabold">
              Ingin Tagihan, DP &amp; Termin Klien Lebih Teratur?
            </h3>
            <p class="text-sm text-slate-300">
              Coba <strong class="text-white">Synbarra InvoiceRapi</strong> — platform SaaS manajemen invoice, jadwal termin pembayaran, dan pemantauan piutang untuk bisnis jasa Indonesia.
            </p>
          </div>
          <div class="flex flex-wrap gap-3 shrink-0">
            <a
              href="https://invoicerapi.synbarra.com/"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#00F1DE] text-[#01232B] text-xs font-bold hover:bg-[#04D2C5] transition-all"
            >
              <span>Coba InvoiceRapi Gratis</span>
              <ExternalLink class="w-4 h-4" />
            </a>
            <Link
              href="/produk/invoicerapi"
              class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 text-white text-xs font-semibold border border-white/20 hover:bg-white/15 transition-all"
            >
              <span>Pelajari Produk</span>
            </Link>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>

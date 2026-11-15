<?php

namespace App\Console\Commands;

use App\Services\GeminiArticleGeneratorService;
use Illuminate\Console\Command;
use Throwable;

class GeneratePromotionalArticle extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'articles:generate
                            {--count=1 : Jumlah artikel yang akan digenerate}
                            {--category= : Slug kategori tertentu (opsional)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate artikel edukatif lintas topik menggunakan Gemini API dengan sisipan promosi Synbarra InvoiceRapi.';

    public function handle(GeminiArticleGeneratorService $generator): int
    {
        $count = max(1, min(5, (int) $this->option('count')));
        $category = $this->option('category') ?: null;

        for ($i = 1; $i <= $count; $i++) {
            $this->info("[{$i}/{$count}] Menghubungi Gemini API untuk membuat artikel...");

            try {
                $post = $generator->generateAndPublish($category);
                $this->info("Berhasil diterbitkan: \"{$post->title}\" (/artikel/{$post->slug})");
            } catch (Throwable $e) {
                $this->error("Gagal membuat artikel ke-{$i}: " . $e->getMessage());
                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('path', 255)->index();
            $table->string('route_name', 100)->nullable();
            $table->string('referrer_host', 150)->nullable();
            $table->string('traffic_source', 40)->default('direct')->index(); // google, meta, ai_search, direct, referral
            $table->string('visitor_type', 40)->default('human')->index(); // human, googlebot, metabot, aibot, other_bot
            $table->string('bot_name', 80)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};


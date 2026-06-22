<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->index();
            $table->string('title');
            $table->string('url')->unique();
            $table->string('content_hash')->index();
            $table->text('excerpt')->nullable();
            $table->longText('raw_content')->nullable();
            $table->string('status')->default('collected')->index();
            $table->json('metadata')->nullable();
            $table->text('ai_summary')->nullable();
            $table->text('ai_why_it_matters')->nullable();
            $table->text('ai_developer_impact')->nullable();
            $table->text('ai_recommended_action')->nullable();
            $table->unsignedInteger('ai_priority_score')->nullable();
            $table->unsignedInteger('ai_confidence_score')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('summarized_at')->nullable();
            $table->timestamps();

            $table->unique(['source_id', 'content_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_updates');
    }
};

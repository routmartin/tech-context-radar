<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('source_id')->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->text('why_it_matters');
            $table->text('developer_impact');
            $table->text('recommended_action');
            $table->unsignedInteger('priority_score')->default(0);
            $table->unsignedInteger('read_time_minutes')->default(3);
            $table->unsignedInteger('source_count')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signals');
    }
};

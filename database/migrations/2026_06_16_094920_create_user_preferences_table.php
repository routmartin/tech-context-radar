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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedInteger('briefing_length_minutes')->default(10);
            $table->unsignedInteger('priority_threshold')->default(70);
            $table->json('preferred_categories')->nullable();
            $table->boolean('daily_reminder_enabled')->default(true);
            $table->boolean('priority_alerts_enabled')->default(true);
            $table->boolean('weekly_summary_enabled')->default(false);
            $table->boolean('compact_mode_enabled')->default(false);
            $table->boolean('dark_mode_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};

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
        Schema::create('briefings', function (Blueprint $table) {
            $table->id();
            $table->date('briefing_date')->unique();
            $table->string('title');
            $table->text('summary');
            $table->unsignedInteger('reading_time_minutes')->default(10);
            $table->unsignedInteger('priority_signal_count')->default(0);
            $table->unsignedInteger('low_impact_filtered_count')->default(0);
            $table->unsignedInteger('confidence_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('briefings');
    }
};

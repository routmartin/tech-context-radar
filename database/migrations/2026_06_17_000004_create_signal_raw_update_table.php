<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signal_raw_update', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('signal_id')->index();
            $table->unsignedBigInteger('raw_update_id')->index();
            $table->timestamps();

            $table->unique(['signal_id', 'raw_update_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signal_raw_update');
    }
};

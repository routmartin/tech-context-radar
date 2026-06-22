<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->string('feed_url')->nullable()->after('slug');
            $table->string('homepage_url')->nullable()->after('feed_url');
            $table->boolean('is_enabled')->default(true)->after('homepage_url');
            $table->text('last_scan_error')->nullable()->after('last_scanned_at');
        });
    }

    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn(['feed_url', 'homepage_url', 'is_enabled', 'last_scan_error']);
        });
    }
};

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
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->string('video')->nullable()->after('mobile_image');
            $table->string('video_url')->nullable()->after('video'); // For external video URLs (YouTube, Vimeo, etc.)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->dropColumn(['video', 'video_url']);
        });
    }
};

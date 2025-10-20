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
        Schema::create('homepage_assets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['image', 'video', 'icon', 'file'])->notNull();
            $table->string('name')->notNull();
            $table->string('path')->notNull();
            $table->string('alt_text')->nullable();
            $table->string('category')->nullable(); // 'hero', 'banner', 'icon', etc.
            $table->json('metadata')->nullable(); // Dimensions, file size, mime type, etc.
            $table->timestamps();
            
            $table->index('category');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_assets');
    }
};

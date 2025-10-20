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
        Schema::table('first_delivery_settings', function (Blueprint $table) {
            $table->text('first_delivery_key')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('first_delivery_settings', function (Blueprint $table) {
            $table->string('first_delivery_key')->nullable()->change();
        });
    }
};
